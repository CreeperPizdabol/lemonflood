const http = require('http');
const fs = require('fs/promises');
const path = require('path');

loadEnvFile();

const PORT = Number(process.env.PORT || 3000);
const BOT_TOKEN = process.env.TELEGRAM_BOT_TOKEN || '';
const TELEGRAM_CHAT_IDS = (process.env.TELEGRAM_CHAT_IDS || '')
  .split(',')
  .map((id) => id.trim())
  .filter(Boolean);

const ROOT = __dirname;
const DB_DIR = path.join(ROOT, 'db');
const DB_FILE = path.join(DB_DIR, 'applications.json');

const contentTypes = {
  '.htm': 'text/html; charset=utf-8',
  '.html': 'text/html; charset=utf-8',
  '.png': 'image/png',
  '.jpg': 'image/jpeg',
  '.jpeg': 'image/jpeg',
  '.css': 'text/css; charset=utf-8',
  '.js': 'text/javascript; charset=utf-8'
};

function loadEnvFile() {
  const envPath = path.join(__dirname, '.env');

  require('fs').existsSync(envPath) && require('fs')
    .readFileSync(envPath, 'utf8')
    .split(/\r?\n/)
    .forEach((line) => {
      const trimmed = line.trim();
      if (!trimmed || trimmed.startsWith('#')) return;

      const separatorIndex = trimmed.indexOf('=');
      if (separatorIndex === -1) return;

      const key = trimmed.slice(0, separatorIndex).trim();
      const value = trimmed.slice(separatorIndex + 1).trim();
      if (key && !process.env[key]) {
        process.env[key] = value;
      }
    });
}

async function ensureDatabase() {
  await fs.mkdir(DB_DIR, { recursive: true });
  try {
    await fs.access(DB_FILE);
  } catch {
    await fs.writeFile(DB_FILE, '[]\n', 'utf8');
  }
}

async function readJsonBody(request) {
  const chunks = [];
  for await (const chunk of request) {
    chunks.push(chunk);
    if (Buffer.concat(chunks).length > 16 * 1024) {
      throw new Error('Слишком большая заявка');
    }
  }

  return JSON.parse(Buffer.concat(chunks).toString('utf8') || '{}');
}

async function saveApplication(application) {
  await ensureDatabase();
  const current = JSON.parse(await fs.readFile(DB_FILE, 'utf8'));
  current.push(application);
  await fs.writeFile(DB_FILE, `${JSON.stringify(current, null, 2)}\n`, 'utf8');
}

function escapeHtml(value) {
  return String(value)
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;');
}

async function sendTelegramMessage(application) {
  if (!BOT_TOKEN || TELEGRAM_CHAT_IDS.length === 0) {
    return;
  }

  const text = [
    'Новая заявка Lemon Flood',
    '',
    `ТГ юз: ${application.telegramUsername}`,
    `Роль: ${application.role}`,
    `Дата: ${application.createdAt}`
  ].join('\n');

  await Promise.all(TELEGRAM_CHAT_IDS.map(async (chatId) => {
    const response = await fetch(`https://api.telegram.org/bot${BOT_TOKEN}/sendMessage`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        chat_id: chatId,
        text,
        parse_mode: 'HTML'
      })
    });

    if (!response.ok) {
      const details = await response.text();
      throw new Error(`Telegram error for ${chatId}: ${details}`);
    }
  }));
}

function sendJson(response, statusCode, payload) {
  response.writeHead(statusCode, { 'Content-Type': 'application/json; charset=utf-8' });
  response.end(JSON.stringify(payload));
}

async function handleApplication(request, response) {
  try {
    const body = await readJsonBody(request);
    const telegramUsername = String(body.telegramUsername || '').trim();
    const role = String(body.role || '').trim();

    if (telegramUsername.length < 2 || role.length < 2) {
      sendJson(response, 400, { error: 'Заполните оба обязательных поля' });
      return;
    }

    const application = {
      id: Date.now().toString(36),
      telegramUsername: escapeHtml(telegramUsername),
      role: escapeHtml(role),
      createdAt: new Date().toISOString()
    };

    await saveApplication(application);
    await sendTelegramMessage(application);
    sendJson(response, 201, { ok: true });
  } catch (error) {
    console.error(error);
    sendJson(response, 500, { error: 'Ошибка отправки заявки' });
  }
}

async function serveStatic(request, response) {
  const requestUrl = new URL(request.url, `http://${request.headers.host}`);
  const fileName = requestUrl.pathname === '/' ? 'index (1).htm' : decodeURIComponent(requestUrl.pathname.slice(1));
  const filePath = path.normalize(path.join(ROOT, fileName));

  if (!filePath.startsWith(ROOT)) {
    response.writeHead(403);
    response.end('Forbidden');
    return;
  }

  try {
    const file = await fs.readFile(filePath);
    response.writeHead(200, {
      'Content-Type': contentTypes[path.extname(filePath).toLowerCase()] || 'application/octet-stream'
    });
    response.end(file);
  } catch {
    response.writeHead(404);
    response.end('Not found');
  }
}

const server = http.createServer(async (request, response) => {
  if (request.method === 'POST' && request.url === '/api/applications') {
    await handleApplication(request, response);
    return;
  }

  if (request.method === 'GET') {
    await serveStatic(request, response);
    return;
  }

  response.writeHead(405);
  response.end('Method not allowed');
});

server.listen(PORT, async () => {
  await ensureDatabase();
  console.log(`Lemon Flood site is running: http://localhost:${PORT}`);
});
