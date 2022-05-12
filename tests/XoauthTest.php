<?php

namespace Javanile\Imap2\Tests;

use Javanile\Imap2\Connection;

class XoauthTest extends ImapTestCase
{
    public function testConnection()
    {
        $imap = imap2_open($this->mailbox, $this->username, $this->accessToken, OP_XOAUTH2);

        $this->assertInstanceOf(Connection::class, $imap);
    }

    public function testList()
    {
        $imap = imap2_open($this->mailbox, $this->username, $this->accessToken, OP_XOAUTH2);
        $list = imap2_list($imap, $this->mailbox, '*');

        var_dump($list);
        die();
        $this->assertInstanceOf(Connection::class, $imap);
    }
}
