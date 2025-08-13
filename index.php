<?php
declare(strict_types=1);

$linksFile = __DIR__ . '/links.txt';
$logFile   = __DIR__ . '/clicks.log';

// ---------- helper ----------
function clientIp(): string {
    // Catat REMOTE_ADDR + indikasi header proxy tanpa mempercayainya sepenuhnya
    $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    $xff = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? null;
    $cf  = $_SERVER['HTTP_CF_CONNECTING_IP'] ?? null;
    // Simpan info tambahan di log, tapi return IP dasar
    return $ip;
}

function isValidUrl(string $url): bool {
    if (!filter_var($url, FILTER_VALIDATE_URL)) return false;
    $parts = parse_url($url);
    if (!$parts) return false;
    $scheme = strtolower($parts['scheme'] ?? '');
    if (!in_array($scheme, ['http', 'https'], true)) return false;

    // (Opsional) whitelist domain:
    // $host = strtolower($parts['host'] ?? '');
    // if (!in_array($host, ['example.com','your-domain.com'], true)) return false;

    return true;
}

function sanitizeLink(string $line): string {
    // Hilangkan control chars + whitespace di ujung
    $line = trim($line);
    // Buang CRLF / control non-printing
    $line = preg_replace('/[\x00-\x1F\x7F]/', '', $line);
    return $line;
}

// ---------- guard ----------
header('X-Content-Type-Options: nosniff');

if (!is_file($linksFile)) {
    http_response_code(500);
    header('Content-Type: text/plain; charset=UTF-8');
    exit("Link file not found.");
}

// ---------- pilih 1 baris acak dengan reservoir sampling (1-pass) ----------
$selectedLink = '';
$lineNum = 0;

$fh = fopen($linksFile, 'rb');
if ($fh === false) {
    http_response_code(500);
    header('Content-Type: text/plain; charset=UTF-8');
    exit("Failed to open links file.");
}

while (($line = fgets($fh)) !== false) {
    $line = sanitizeLink($line);
    if ($line === '') continue;

    $lineNum++;
    // Ganti pilihan dengan probabilitas 1/$lineNum
    if (random_int(1, $lineNum) === 1) {
        $selectedLink = $line;
    }
}
fclose($fh);

if ($selectedLink === '') {
    http_response_code(500);
    header('Content-Type: text/plain; charset=UTF-8');
    exit("No links available.");
}

if (!isValidUrl($selectedLink)) {
    http_response_code(400);
    header('Content-Type: text/plain; charset=UTF-8');
    exit("Invalid link.");
}

// ---------- logging aman (JSONL + LOCK_EX) ----------
$logData = [
    'time'       => date('c'),
    'ip'         => clientIp(),
    'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown',
    'referer'    => $_SERVER['HTTP_REFERER'] ?? null,
    'link'       => $selectedLink,
];

@file_put_contents(
    $logFile,
    json_encode($logData, JSON_UNESCAPED_SLASHES) . PHP_EOL,
    FILE_APPEND | LOCK_EX
);

// ---------- redirect ----------
header('Cache-Control: no-store');
header('Location: ' . $selectedLink, true, 302);
exit;
