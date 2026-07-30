<?php

session_start();
header('Content-Type: application/json; charset=utf-8');

const COOLDOWN_SECONDS = 86400;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendJson(405, ['error' => 'Method not allowed']);
}

$configPath = __DIR__ . '/config.php';
if (!file_exists($configPath)) {
    sendJson(500, ['error' => 'Не найден config.php']);
}

$config = require $configPath;
$data = json_decode(file_get_contents('php://input'), true);

if (!is_array($data)) {
    sendJson(400, ['error' => 'Некорректные данные формы']);
}

$telegramUsername = trim((string)($data['telegramUsername'] ?? ''));
$role = trim((string)($data['role'] ?? ''));
$captcha = trim((string)($data['captcha'] ?? ''));

if (mb_strlen($telegramUsername) < 2 || mb_strlen($role) < 2) {
    sendJson(400, ['error' => 'Заполните оба обязательных поля']);
}

$captchaAnswer = (string)($_SESSION['captcha_answer'] ?? '');
unset($_SESSION['captcha_answer']);

if ($captchaAnswer === '' || !hash_equals($captchaAnswer, $captcha)) {
    sendJson(400, ['error' => 'Неверная капча']);
}

$visitor = getVisitorInfo($data);
$applications = readApplications();

if (hasRecentApplication($applications, $visitor['fingerprint'])) {
    sendJson(429, ['error' => 'Заявку можно отправлять только один раз в день']);
}

$application = [
    'id' => base_convert((string)time(), 10, 36) . '-' . bin2hex(random_bytes(3)),
    'telegramUsername' => $telegramUsername,
    'role' => $role,
    'createdAt' => gmdate('c'),
    'visitor' => $visitor,
];

try {
    saveApplication($application);
    sendTelegramMessage($config, $application);
    sendJson(201, ['ok' => true]);
} catch (Throwable $error) {
    error_log($error->getMessage());
    sendJson(500, ['error' => 'Ошибка отправки заявки']);
}

function sendJson(int $statusCode, array $payload): void
{
    http_response_code($statusCode);
    echo json_encode($payload, JSON_UNESCAPED_UNICODE);
    exit;
}

function readApplications(): array
{
    $dbFile = __DIR__ . '/db/applications.json';
    if (!file_exists($dbFile)) {
        return [];
    }

    $contents = file_get_contents($dbFile);
    $applications = $contents ? json_decode($contents, true) : [];

    return is_array($applications) ? $applications : [];
}

function saveApplication(array $application): void
{
    $dbDir = __DIR__ . '/db';
    $dbFile = $dbDir . '/applications.json';

    if (!is_dir($dbDir)) {
        mkdir($dbDir, 0755, true);
    }

    $handle = fopen($dbFile, 'c+');
    if ($handle === false) {
        throw new RuntimeException('Cannot open database file');
    }

    try {
        flock($handle, LOCK_EX);
        rewind($handle);
        $contents = stream_get_contents($handle);
        $applications = $contents ? json_decode($contents, true) : [];

        if (!is_array($applications)) {
            $applications = [];
        }

        $applications[] = $application;
        rewind($handle);
        ftruncate($handle, 0);
        fwrite($handle, json_encode($applications, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . PHP_EOL);
    } finally {
        flock($handle, LOCK_UN);
        fclose($handle);
    }
}

function hasRecentApplication(array $applications, string $fingerprint): bool
{
    $now = time();

    foreach ($applications as $application) {
        if (!is_array($application)) {
            continue;
        }

        $createdAt = strtotime((string)($application['createdAt'] ?? ''));
        if ($createdAt === false || ($now - $createdAt) > COOLDOWN_SECONDS) {
            continue;
        }

        $visitor = $application['visitor'] ?? [];
        $oldFingerprint = is_array($visitor)
            ? (string)($visitor['fingerprint'] ?? '')
            : hash('sha256', (string)($application['ip'] ?? ''));

        if ($oldFingerprint !== '' && hash_equals($oldFingerprint, $fingerprint)) {
            return true;
        }
    }

    return false;
}

function getVisitorInfo(array $data): array
{
    $ip = getClientIp();
    $userAgent = (string)($_SERVER['HTTP_USER_AGENT'] ?? '');
    $acceptLanguage = (string)($_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? '');
    $timezone = trim((string)($data['timezone'] ?? ''));
    $language = trim((string)($data['language'] ?? ''));
    $platform = trim((string)($data['platform'] ?? ''));
    $fingerprintSource = implode('|', [$ip, $userAgent, $acceptLanguage, $timezone, $language, $platform]);

    return [
        'ip' => $ip,
        'forwardedFor' => (string)($_SERVER['HTTP_X_FORWARDED_FOR'] ?? ''),
        'realIp' => (string)($_SERVER['HTTP_X_REAL_IP'] ?? ''),
        'userAgent' => $userAgent,
        'acceptLanguage' => $acceptLanguage,
        'referer' => (string)($_SERVER['HTTP_REFERER'] ?? ''),
        'page' => trim((string)($data['page'] ?? '')),
        'timezone' => $timezone,
        'language' => $language,
        'platform' => $platform,
        'fingerprint' => hash('sha256', $fingerprintSource),
    ];
}

function getClientIp(): string
{
    foreach (['HTTP_CF_CONNECTING_IP', 'HTTP_X_REAL_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR'] as $header) {
        $value = (string)($_SERVER[$header] ?? '');
        if ($value === '') {
            continue;
        }

        $ip = trim(explode(',', $value)[0]);
        if (filter_var($ip, FILTER_VALIDATE_IP)) {
            return $ip;
        }
    }

    return '';
}

function sendTelegramMessage(array $config, array $application): void
{
    $botToken = trim((string)($config['bot_token'] ?? ''));
    $chatIds = $config['chat_ids'] ?? [];

    if ($botToken === '' || !is_array($chatIds) || count($chatIds) === 0) {
        return;
    }

    $visitor = $application['visitor'];
    $text = implode("\n", [
        'Новая заявка Lemon Flood',
        '',
        'ТГ юз: ' . $application['telegramUsername'],
        'Роль: ' . $application['role'],
        'Дата: ' . $application['createdAt'],
        '',
        'IP: ' . $visitor['ip'],
        'Forwarded-For: ' . $visitor['forwardedFor'],
        'Real-IP: ' . $visitor['realIp'],
        'User-Agent: ' . $visitor['userAgent'],
        'Accept-Language: ' . $visitor['acceptLanguage'],
        'Referrer: ' . $visitor['referer'],
        'Страница: ' . $visitor['page'],
        'Часовой пояс: ' . $visitor['timezone'],
        'Язык браузера: ' . $visitor['language'],
        'Платформа: ' . $visitor['platform'],
    ]);

    foreach ($chatIds as $chatId) {
        $chatId = trim((string)$chatId);
        if ($chatId === '') {
            continue;
        }

        $response = sendPostRequest('https://api.telegram.org/bot' . $botToken . '/sendMessage', [
            'chat_id' => $chatId,
            'text' => $text,
        ]);
        $decoded = json_decode($response, true);

        if (!is_array($decoded) || ($decoded['ok'] ?? false) !== true) {
            throw new RuntimeException('Telegram send error: ' . $response);
        }
    }
}

function sendPostRequest(string $url, array $payload): string
{
    if (function_exists('curl_init')) {
        $curl = curl_init($url);
        curl_setopt_array($curl, [
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
            CURLOPT_POSTFIELDS => json_encode($payload, JSON_UNESCAPED_UNICODE),
            CURLOPT_TIMEOUT => 10,
        ]);

        $response = curl_exec($curl);
        $error = curl_error($curl);
        curl_close($curl);

        if ($response === false) {
            throw new RuntimeException('Curl error: ' . $error);
        }

        return $response;
    }

    $context = stream_context_create([
        'http' => [
            'method' => 'POST',
            'header' => "Content-Type: application/json\r\n",
            'content' => json_encode($payload, JSON_UNESCAPED_UNICODE),
            'timeout' => 10,
        ],
    ]);

    $response = file_get_contents($url, false, $context);
    if ($response === false) {
        throw new RuntimeException('HTTP request error');
    }

    return $response;
}
