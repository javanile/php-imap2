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

        $this->assertInstanceOf(Connection::class, $imap);
    }

    public function testCreateMailbox()
    {
        $imap = imap2_open($this->mailbox, $this->username, $this->accessToken, OP_XOAUTH2);
        $randomMailboxName = 'Mailbox-'.md5(time().rand(1000, 9999));
        $success = imap2_createmailbox($imap, $randomMailboxName);
        $list = imap2_list($imap, $this->mailbox, '*');

        $this->assertInstanceOf(Connection::class, $imap);
    }

}
