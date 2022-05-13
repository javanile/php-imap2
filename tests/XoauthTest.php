<?php

namespace Javanile\Imap2\Tests;

use Javanile\Imap2\Connection;

class XoauthTest extends ImapTestCase
{
    /*
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

        $this->assertTrue($success);
        $this->assertContains($this->mailbox.$randomMailboxName, $list);
    }

    public function testDeleteMailbox()
    {
        $imap = imap2_open($this->mailbox, $this->username, $this->accessToken, OP_XOAUTH2);
        $randomMailboxName = 'Mailbox-'.md5(time().rand(1000, 9999));
        $success = imap2_createmailbox($imap, $randomMailboxName);
        $list = imap2_list($imap, $this->mailbox, '*');

        $this->assertTrue($success);
        $this->assertContains($this->mailbox.$randomMailboxName, $list);

        $success = imap2_deletemailbox($imap, $randomMailboxName);
        $list = imap2_list($imap, $this->mailbox, '*');

        $this->assertTrue($success);
        $this->assertNotContains($this->mailbox.$randomMailboxName, $list);
    }

    public function testFetchBody()
    {
        $imap = imap2_open($this->mailbox, $this->username, $this->accessToken, OP_XOAUTH2);

        $body = imap2_fetchbody($imap, 1, null);
        var_dump($body);
        die();

        $this->assertTrue($success);
        $this->assertNotContains($this->mailbox.$randomMailboxName, $list);
    }
    */

    /*
    public function testDelete()
    {
        $imap = imap2_open($this->mailbox, $this->username, $this->accessToken, OP_XOAUTH2);

        $check = imap2_check($imap);
        $initialCount = $check->Nmsgs;

        $deleteSuccess = imap2_delete($imap, '1:2');
        $expungeSuccess = imap2_expunge($imap);

        $check = imap2_check($imap);
        $finalCount = $check->Nmsgs;

        $this->assertTrue($deleteSuccess);
        $this->assertTrue($expungeSuccess);
        $this->assertEquals($initialCount - 2, $finalCount);
    }
    */

    /*
    public function testUndelete()
    {
        $imap = imap2_open($this->mailbox, $this->username, $this->accessToken, OP_XOAUTH2);

        $check = imap2_check($imap);
        $initialCount = $check->Nmsgs;

        $deleteSuccess = imap2_delete($imap, '1:2');
        $undeleteSuccess = imap2_undelete($imap, '1:2');
        $expungeSuccess = imap2_expunge($imap);

        $check = imap2_check($imap);
        $finalCount = $check->Nmsgs;

        $this->assertTrue($deleteSuccess);
        $this->assertTrue($undeleteSuccess);
        $this->assertTrue($expungeSuccess);
        $this->assertEquals($initialCount, $finalCount);
    }
    */

    /*
    public function testSearch()
    {
        $imap = imap2_open($this->mailbox, $this->username, $this->accessToken, OP_XOAUTH2);

        $this->assertTrue(imap2_expunge($imap));
        $this->assertTrue(imap2_delete($imap, '4:5'));

        $deletedMessages = imap2_search($imap, 'DELETED');

        $this->assertEquals(['4', '5'], $deletedMessages);
    }
    */

    /*
    public function testThread()
    {
        $imap = imap2_open($this->mailbox, $this->username, $this->accessToken, OP_XOAUTH2);

        $thread = imap2_thread($imap);

        $this->assertEquals(['4', '5'], $thread);
    }
    */

    public function testStatus()
    {
        $imap = imap2_open($this->mailbox, $this->username, $this->accessToken, OP_XOAUTH2);

        $status = imap2_status($imap, SA_ALL);

        var_dump($status);

        //$this->assertEquals(['4', '5'], $status);
    }
}
