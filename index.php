<?php
$linksFile = __DIR__ . '/links.txt';
$logFile = __DIR__ . '/clicks.log';

// Cek apakah file links tersedia
if (!file_exists($linksFile)) {
    http_response_code(500);
    exit("Link file not found.");
}

// Hitung jumlah baris valid
$total = 0;
$handle = fopen($linksFile, "r");
while (!feof($handle)) {
    $line = trim(fgets($handle));
    if ($line !== "") $total++;
}
fclose($handle);

if ($total === 0) {
    http_response_code(500);
    exit("No links available.");
}

// Pilih acak baris ke-n
$target = rand(1, $total);
$current = 0;
$selectedLink = "";

$handle = fopen($linksFile, "r");
while (!feof($handle)) {
    $line = trim(fgets($handle));
    if ($line === "") continue;
    $current++;
    if ($current === $target) {
        $selectedLink = $line;
        break;
    }
}
fclose($handle);

// Jika ada link yang valid, log klik lalu redirect
if ($selectedLink) {
    // Simpan log
    $ip = $_SERVER['REMOTE_ADDR'];
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
    $time = date('Y-m-d H:i:s');
    $logEntry = "[$time] $ip - $selectedLink - $userAgent\n";
    file_put_contents($logFile, $logEntry, FILE_APPEND);

    // Redirect
    header("Location: $selectedLink");
    exit;
}

http_response_code(500);
exit("Failed to select link.");
