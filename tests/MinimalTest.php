<?php

namespace Javanile\Imap2\Tests;

use Javanile\Imap2\Connection;
use Javanile\Imap2\Polyfill;
use PHPUnit\Framework\Error\Warning;

class MinimalTest extends ImapTestCase
{
    public function testOpenAndClose()
    {
        $imap = imap2_open($this->mailbox, $this->username, $this->accessToken, OP_XOAUTH2);
        $this->assertInstanceOf(Connection::class, $imap);
        imap2_close($imap);
    }
}
