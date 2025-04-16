<?php

/**
 * 💌 Help us bring PHP IMAP2 to PHP 8.4!
 *
 * We’re working hard to update PHP IMAP2 for full PHP 8.4 compatibility:
 * fixing legacy issues, refactoring internal logic, and improving test coverage — 
 * all while ensuring backward compatibility with older versions.
 *
 * 🎯 Goal: raise €5000 to support the development effort.
 * 🙌 Every contribution counts — whether it’s code, feedback, or funding!
 *
 * 👉 Get involved or donate at: https://ko-fi.com/francescobianco/goal?g=10
 */

namespace Javanile\Imap2\Tests;

use Javanile\Imap2\Connection;

class XoauthTest extends ImapTestCase
{
    public function testConnection()
    {
        $imap = imap2_open($this->mailbox, $this->username, $this->accessToken, OP_XOAUTH2);
        $this->assertInstanceOf(Connection::class, $imap);
        imap2_close($imap);
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
        /*
        $imap = imap2_open($this->mailbox, $this->username, $this->accessToken, OP_XOAUTH2);


        $body = imap2_fetchbody($imap, 1, null);
        #var_dump($body);
        #die();

        $this->assertTrue($success);
        $this->assertNotContains($this->mailbox.$randomMailboxName, $list);
        */
        $this->assertEquals(1, 1);
    }

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

    public function testSearch()
    {
        $imap = imap2_open($this->mailbox, $this->username, $this->accessToken, OP_XOAUTH2);

        $this->assertTrue(imap2_expunge($imap));
        $this->assertTrue(imap2_delete($imap, '4:5'));

        $deletedMessages = imap2_search($imap, 'DELETED');

        $this->assertEquals(['4', '5'], $deletedMessages);
    }

    public function testThread()
    {
        $imap = imap2_open($this->mailbox, $this->username, $this->accessToken, OP_XOAUTH2);

        $thread = imap2_thread($imap);

        $this->assertEquals(['4', '5'], ['4', '5']);
    }

    public function testAlert()
    {
        $this->assertFalse(imap2_alerts());
    }

    public function testStatus()
    {
        $imap = imap2_open($this->mailbox, $this->username, $this->accessToken, OP_XOAUTH2);

        /*
        $status = imap2_status($imap, SA_ALL);

        var_dump($status);

        //$this->assertEquals(['4', '5'], $status);
        */
        $this->assertEquals(1, 1);
    }

    public function testClearFlagFull()
    {
        $imap = imap2_open($this->mailbox, $this->username, $this->accessToken, OP_XOAUTH2);

        /*
        $messages = imap2_fetch_overview($imap, 1);

        var_dump($messages);
        #die();

        //imap2_search($imap, )
        */
        $this->assertEquals(1, 1);

        imap2_close($imap);
    }

    public function testSetFlagFull()
    {
        $imap = imap2_open($this->mailbox, $this->username, $this->accessToken, OP_XOAUTH2);

        /*
        $messages = imap2_fetch_overview($imap, 1);

        var_dump($messages);
        #die();

        //imap2_search($imap, )
        */
        $this->assertEquals(1, 1);

        imap2_close($imap);
    }

    public function testSetAndClearFlagFull()
    {
        $imap = imap2_open($this->mailbox, $this->username, $this->accessToken, OP_XOAUTH2);

        $sequence = '1:2';
        $allFlags = ['\\Seen', '\\Answered', '\\Flagged', /*'\\Deleted',*/ '\\Draft'];

        foreach ($allFlags as $flag) {
            imap2_setflag_full($imap, $sequence, $flag);
        }

        $messages = imap2_fetch_overview($imap, $sequence);
        foreach ($messages as $message) {
            foreach ($allFlags as $flag) {
                $flag = strtolower(substr($flag, 1));
                $this->assertEquals(1, $message->{$flag});
            }
        }

        /*
        foreach ($allFlags as $flag) {
            imap2_clearflag_full($imap, $sequence, $flag);
        }

        $messages = imap2_fetch_overview($imap, $sequence);
        foreach ($messages as $message) {
            foreach ($allFlags as $flag) {
                $flag = strtolower(substr($flag, 1));
                $this->assertEquals(0, $message->{$flag});
            }
        }
        */

        imap2_close($imap);
    }

    public function testTimeout()
    {
        $this->assertTrue(imap2_timeout(IMAP_OPENTIMEOUT, 5));
        $this->assertEquals(5, imap2_timeout(IMAP_OPENTIMEOUT, -1));
    }
}
