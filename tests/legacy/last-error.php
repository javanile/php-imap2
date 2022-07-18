<?php

if (!function_exists('imap_open')) {
    include_once __DIR__.'/../../vendor/autoload.php';
}


/*
$mailbox = '...';
$username = '...';
$password = '...';

$imap = imap_open($mailbox, $username, $password);

var_dump(imap_last_error());
*/

$mailbox = '{imap.gmail.com:993/imap/ssl}';
$username = 'wrong-username';
$password = 'wrong-password';

$imap = imap_open($mailbox, $username, $password);

var_dump(imap_last_error());
