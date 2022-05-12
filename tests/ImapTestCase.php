<?php

namespace Javanile\Imap2\Tests;

use PHPUnit\Framework\TestCase;

class ImapTestCase extends TestCase
{
    protected $mailbox;
    protected $username;
    protected $password;
    protected $accessToken;
    protected $message;

    public function setUp()
    {
        $this->mailbox = getenv('IMAP_MAILBOX');
        $this->username = getenv('IMAP_USERNAME');
        $this->password = getenv('IMAP_PASSWORD');
        $this->accessToken = getenv('IMAP_ACCESS_TOKEN');
        $this->message = file_get_contents('tests/fixtures/message.eml');
    }
}
