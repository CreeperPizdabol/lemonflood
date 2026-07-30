<?php

session_start();
header('Content-Type: application/json; charset=utf-8');

$left = random_int(2, 19);
$right = random_int(2, 19);
$operators = ['+', '-'];
$operator = $operators[random_int(0, count($operators) - 1)];

if ($operator === '-' && $right > $left) {
    [$left, $right] = [$right, $left];
}

$answer = $operator === '+'
    ? $left + $right
    : $left - $right;

$_SESSION['captcha_answer'] = (string)$answer;

echo json_encode([
    'question' => 'Капча: сколько будет ' . $left . ' ' . $operator . ' ' . $right . '?',
], JSON_UNESCAPED_UNICODE);
