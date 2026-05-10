<?php

$envFile = __DIR__ . '/../.env';
$envExampleFile = __DIR__ . '/../tests/.env.examples';
$redirectUri = 'http://localhost:8080';
$scopes = ['https://mail.google.com/'];

function updateEnv($file, $updates) {
    $content = file_exists($file) ? file_get_contents($file) : '';
    $lines = explode("\n", $content);
    $newLines = [];
    $found = [];
    foreach ($lines as $line) {
        $parts = explode('=', $line, 2);
        if (count($parts) === 2 && array_key_exists($parts[0], $updates)) {
            $newLines[] = $parts[0] . '=' . $updates[$parts[0]];
            $found[$parts[0]] = true;
        } else {
            $newLines[] = $line;
        }
    }
    foreach ($updates as $key => $value) {
        if (!isset($found[$key])) {
            $newLines[] = $key . '=' . $value;
        }
    }
    file_put_contents($file, rtrim(implode("\n", $newLines)) . "\n");
}

function getEnvVar($file, $key) {
    if (!file_exists($file)) return '';
    $lines = explode("\n", file_get_contents($file));
    foreach ($lines as $line) {
        $parts = explode('=', $line, 2);
        if (count($parts) === 2 && $parts[0] === $key) {
            return trim($parts[1]);
        }
    }
    return '';
}

if (php_sapi_name() === 'cli') {
    echo "\n═══════════════════════════════════════════════\n";
    echo "  php-imap2 Development Environment Setup\n";
    echo "═══════════════════════════════════════════════\n\n";

    if (!file_exists($envFile)) {
        if (file_exists($envExampleFile)) {
            copy($envExampleFile, $envFile);
            echo "  ✔ .env created from template\n";
        } else {
            touch($envFile);
            echo "  ✔ .env file created\n";
        }
    } else {
        echo "  ✔ .env file already exists\n";
    }

    $clientId = getEnvVar($envFile, 'GOOGLE_CLIENT_ID');
    $prompt = $clientId ? "Google Client ID [{$clientId}]: " : "Google Client ID: ";
    echo $prompt;
    $input = trim(fgets(STDIN));
    if ($input !== '') $clientId = $input;

    $clientSecret = getEnvVar($envFile, 'GOOGLE_CLIENT_SECRET');
    $prompt = $clientSecret ? "Google Client Secret [{$clientSecret}]: " : "Google Client Secret: ";
    echo $prompt;
    $input = trim(fgets(STDIN));
    if ($input !== '') $clientSecret = $input;

    updateEnv($envFile, [
        'GOOGLE_CLIENT_ID' => $clientId,
        'GOOGLE_CLIENT_SECRET' => $clientSecret
    ]);

    echo "  ✔ Credentials saved to .env\n\n";

    echo "  Starting local server to authenticate...\n";
    echo "  Please open http://localhost:8080 in your browser.\n\n";

    // Start the PHP built-in web server routing to this same file
    $cmd = sprintf(
        'php -S 0.0.0.0:8080 -t %s %s',
        escapeshellarg(__DIR__),
        escapeshellarg(__FILE__)
    );
    passthru($cmd);
    exit;
}

// Router Mode
$clientId = getEnvVar($envFile, 'GOOGLE_CLIENT_ID');
$clientSecret = getEnvVar($envFile, 'GOOGLE_CLIENT_SECRET');

$authUri = 'https://accounts.google.com/o/oauth2/v2/auth'
         . '?client_id=' . urlencode($clientId)
         . '&scope=' . urlencode(implode(' ', $scopes))
         . '&redirect_uri=' . urlencode($redirectUri)
         . '&response_type=code'
         . '&prompt=consent'
         . '&access_type=offline';

$tokenUri = 'https://accounts.google.com/o/oauth2/token';

if (empty($_GET['code'])) {
    header('Location: ' . $authUri);
    exit;
}

$postFields = http_build_query([
    'client_id' => $clientId,
    'redirect_uri' => $redirectUri,
    'client_secret' => $clientSecret,
    'code' => $_GET['code'],
    'grant_type' => 'authorization_code',
]);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $tokenUri);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
$response = json_decode(curl_exec($ch), true);
curl_close($ch);

if (!empty($response['access_token'])) {
    updateEnv($envFile, [
        'IMAP_ACCESS_TOKEN' => $response['access_token']
    ]);
    if (!empty($response['refresh_token'])) {
        updateEnv($envFile, [
            'GOOGLE_REFRESH_TOKEN' => $response['refresh_token']
        ]);
    }

    echo '<html><body style="font-family:sans-serif;padding:2em">';
    echo '<h1 style="color:green;">✓ Authorization successful!</h1>';
    echo '<p>Tokens have been saved to .env</p>';
    echo '<p>You can close this window and stop the terminal process (Ctrl+C).</p>';
    echo '</body></html>';
} else {
    echo '<html><body style="font-family:sans-serif;padding:2em">';
    echo '<h1 style="color:red;">✗ Authorization failed</h1>';
    echo '<pre>' . htmlspecialchars(print_r($response, true)) . '</pre>';
    echo '<p><a href="/">Try again</a></p>';
    echo '</body></html>';
}
