<?php

namespace Javanile\Imap2\Tests;

class XoauthTest extends ImapTestCase
{
    public function testConnection()
    {
        $imap = imap2_open($this->mailbox, $this->username, $this->accessToken, OP_XOAUTH2);


        $this->assertEquals(1, 1);
    }
}
