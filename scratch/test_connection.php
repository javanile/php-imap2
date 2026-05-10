<?php

require_once __DIR__ . '/../vendor/autoload.php';

$envFile = __DIR__ . '/../.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        if (strpos($line, '=') === false) continue;
        list($name, $value) = explode('=', $line, 2);
        putenv(trim($name) . '=' . trim($value));
    }
}

$mailbox = getenv('IMAP_MAILBOX') ?: '{imap.gmail.com:993/imap/ssl}INBOX';
$username = getenv('IMAP_USERNAME');
$password = getenv('IMAP_PASSWORD');
$accessToken = getenv('IMAP_ACCESS_TOKEN');

echo "Mailbox: $mailbox\n";
echo "Username: $username\n";
echo "Password: " . ($password && $password !== '...' ? '*** (SET)' : 'NOT SET OR "..."') . "\n";
echo "Access Token: " . ($accessToken ? '*** (SET)' : 'NOT SET') . "\n";
echo str_repeat('-', 50) . "\n\n";

// --- Legacy IMAP ---
echo "1. Legacy IMAP Connection (imap_open)\n";
$legacyConn = @imap_open($mailbox, $username, $password);
if ($legacyConn) {
    echo "   [SUCCESS] Connected.\n";
    $check = imap_check($legacyConn);
    echo "   Messages: " . $check->Nmsgs . "\n";
    imap_close($legacyConn);
} else {
    echo "   [FAILED] Could not connect.\n";
    echo "   Error: " . imap_last_error() . "\n";
}

echo "\n";

// --- IMAP2 with Access Token ---
echo "2. IMAP2 Connection with OAuth (imap2_open)\n";
try {
    $imap2Conn = @imap2_open($mailbox, $username, $accessToken, OP_XOAUTH2);
    if ($imap2Conn) {
        echo "   [SUCCESS] Connected.\n";
        $check = imap2_check($imap2Conn);
        echo "   Messages: " . $check->Nmsgs . "\n";
        imap2_close($imap2Conn);
    } else {
        echo "   [FAILED] Could not connect.\n";
        echo "   Error: " . imap2_last_error() . "\n";
    }
} catch (\Exception $e) {
    echo "   [EXCEPTION] " . $e->getMessage() . "\n";
}

echo "\n";
