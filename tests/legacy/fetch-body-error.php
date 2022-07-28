<?php

if (!function_exists('imap_open')) {
    include_once __DIR__.'/../../vendor/autoload.php';
}

$env = parse_ini_file('.env');

$imap = imap_open($env['IMAP_MAILBOX'], $env['IMAP_USERNAME'], $env['IMAP_PASSWORD']);

$body = imap_fetchbody($imap, 9999, null);

var_dump($body);

var_dump(imap_last_error());

var_dump(imap_errors());

var_dump(imap_alerts());
