<?php

namespace Javanile\Imap2\Tests;

use Javanile\Imap2\Connection;
use Javanile\Imap2\Functions;
use PHPUnit\Framework\Error\Warning;

class CompatibilityTest extends ImapTestCase
{
    public function testOpenAndClose()
    {
        $username = 'wrong@username.local';
        $imap1 = @imap_open($this->mailbox, $username, $this->password);
        $error1 = @imap_errors();
        $imap2 = @imap2_open($this->mailbox, $username, $this->accessToken, OP_XOAUTH2);
        $error2 = @imap2_errors();

        $this->assertEquals($imap1, $imap2);

        $imap1 = imap_open($this->mailbox, $this->username, $this->password);
        $imap2 = imap2_open($this->mailbox, $this->username, $this->accessToken, OP_XOAUTH2);

        $this->assertTrue(is_resource($imap1));
        $this->assertInstanceOf(Connection::class, $imap2);

        $close1 = imap_close($imap1);
        $close2 = imap2_close($imap2);

        $this->assertEquals($close1, $close2);
    }

    public function testAppend()
    {
        $imap1 = imap_open($this->mailbox, $this->username, $this->password);
        $imap2 = imap2_open($this->mailbox, $this->username, $this->accessToken, OP_XOAUTH2);

        $check1 = imap_check($imap1);
        $check2 = imap2_check($imap2);

        $this->assertEquals($check1->Nmsgs, $check2->Nmsgs);

        $initialCount = $check1->Nmsgs;

        imap_append($imap1, $this->mailbox, $this->message);
        imap2_append($imap2, $this->mailbox, $this->message);

        $check1 = imap_check($imap1);
        $check2 = imap2_check($imap2);

        $this->assertEquals($check1->Nmsgs, $check2->Nmsgs);

        $finalCount = $check1->Nmsgs;

        imap_close($imap1);
        imap2_close($imap2);

        $this->assertEquals($initialCount + 2, $finalCount);
    }

    public function testList()
    {
        $imap1 = imap_open($this->mailbox, $this->username, $this->password);
        $imap2 = imap2_open($this->mailbox, $this->username, $this->accessToken, OP_XOAUTH2);

        $boxes1 = imap_list($imap1, $this->mailbox, '*');
        $boxes2 = imap2_list($imap2, $this->mailbox, '*');

        imap_close($imap1);
        imap2_close($imap2);

        $this->assertEquals($boxes1, $boxes2);
    }

    public function testCheck()
    {
        $imap1 = imap_open($this->mailbox, $this->username, $this->password);
        $imap2 = imap2_open($this->mailbox, $this->username, $this->accessToken, OP_XOAUTH2);

        $check1 = imap_check($imap1);
        $check2 = imap2_check($imap2);

        $check2->Date = $check1->Date;
        $check2->Mailbox = $check1->Mailbox;

        imap_close($imap1);
        imap2_close($imap2);

        $this->assertEquals($check1, $check2);
    }

    public function testSearch()
    {
        $imap1 = imap_open($this->mailbox, $this->username, $this->password);
        $imap2 = imap2_open($this->mailbox, $this->username, $this->accessToken, OP_XOAUTH2);

        $boxes1 = imap_list($imap1, $this->mailbox, '*');
        $boxes2 = imap2_list($imap2, $this->mailbox, '*');

        imap_close($imap1);
        imap2_close($imap2);

        $this->assertEquals($boxes1, $boxes2);
    }

    public function testFetchBody()
    {
        $imap1 = imap_open($this->mailbox, $this->username, $this->password);
        $imap2 = imap2_open($this->mailbox, $this->username, $this->accessToken, OP_XOAUTH2);

        for ($message = 1; $message < 10; $message++) {
            foreach ([null, 1] as $section) {
                $body1 = imap_fetchbody($imap1, $message, $section);
                file_put_contents('t1.txt', $body1);
                $body2 = imap2_fetchbody($imap2, $message, $section);
                file_put_contents('t2.txt', $body2);
                $this->assertEquals($body1, $body2);
            }
        }

        imap_close($imap1);
        imap2_close($imap2);
    }

    public function testDelete()
    {
        $imap1 = imap_open($this->mailbox, $this->username, $this->password);
        $imap2 = imap2_open($this->mailbox, $this->username, $this->accessToken, OP_XOAUTH2);

        $messages = '4:6';

        imap_expunge($imap1);

        $this->assertFalse(imap_search($imap1, 'DELETED'));
        $this->assertTrue(imap_delete($imap1, $messages));
        $deletedMessages1 = imap_search($imap1, 'DELETED');
        $deletedMessagesUid1 = imap_search($imap1, 'DELETED', SE_UID);
        $this->assertTrue(imap_undelete($imap1, $messages));
        $this->assertFalse(imap_search($imap1, 'DELETED'));

        $this->assertFalse(imap2_search($imap2, 'DELETED'));
        $this->assertTrue(imap2_delete($imap2, $messages));
        $deletedMessages2 = imap2_search($imap2, 'DELETED');
        $deletedMessagesUid2 = imap2_search($imap2, 'DELETED', SE_UID);
        $this->assertTrue(imap2_undelete($imap2, $messages));
        $this->assertFalse(imap2_search($imap2, 'DELETED'));

        $this->assertEquals($deletedMessages1, $deletedMessages2);
        $this->assertEquals($deletedMessagesUid1, $deletedMessagesUid2);

        imap_close($imap1);
        imap2_close($imap2);
    }

    public function testStatus()
    {
        $imap1 = imap_open($this->mailbox, $this->username, $this->password);
        $imap2 = imap2_open($this->mailbox, $this->username, $this->accessToken, OP_XOAUTH2);

        $status1 = imap_status($imap1, $this->mailbox, SA_ALL);
        $status2 = imap2_status($imap2, $this->mailbox, SA_ALL);

        unset($status1->flags);

        $this->assertEquals($status1, $status2);

        imap_close($imap1);
        imap2_close($imap2);
    }

    public function testAlerts()
    {
        @imap_open('wrong-mailbox', $this->username, $this->password);
        @imap2_open('wrong-mailbox', $this->username, $this->accessToken, OP_XOAUTH2);

        $alerts1 = imap_alerts();
        $alerts2 = imap_alerts();

        $this->assertEquals($alerts1, $alerts2);

        $imap1 = imap_open($this->mailbox, $this->username, $this->password);
        $imap2 = imap2_open($this->mailbox, $this->username, $this->accessToken, OP_XOAUTH2);

        imap_deletemailbox($imap1, 'wrong-mailbox');

        $alerts1 = imap_alerts();
        $alerts2 = imap_alerts();

        $this->assertEquals($alerts1, $alerts2);

        imap_close($imap1);
        imap2_close($imap2);
    }

    public function testErrors()
    {
        $imap1 = imap_open($this->mailbox, $this->username, $this->password);
        $imap2 = imap2_open($this->mailbox, $this->username, $this->accessToken, OP_XOAUTH2);

        imap_deletemailbox($imap1, 'wrong-mailbox');
        $errors1 = imap_errors();

        var_dump($errors1);
        die();

        $status2 = imap2_status($imap2, $this->mailbox, SA_ALL);

        unset($status1->flags);

        $this->assertEquals($status1, $status2);

        imap_close($imap1);
        imap2_close($imap2);
    }

    public function testFetchOverview()
    {
        $imap1 = imap_open($this->mailbox, $this->username, $this->password);
        $imap2 = imap2_open($this->mailbox, $this->username, $this->accessToken, OP_XOAUTH2);

        $sequence = '1:2';
        $overview1 = imap_fetch_overview($imap1, $sequence);
        $overview2 = imap2_fetch_overview($imap2, $sequence);

        $this->assertEquals($overview1, $overview2);

        imap_close($imap1);
        imap2_close($imap2);
    }

    public function testUid()
    {
        $imap1 = imap_open($this->mailbox, $this->username, $this->password);
        $imap2 = imap2_open($this->mailbox, $this->username, $this->accessToken, OP_XOAUTH2);

        $messages = imap_fetch_overview($imap1, '1:5');
        $this->assertCount(5, $messages);

        foreach ($messages as $message) {
            $uid1 = imap_uid($imap1, $message->msgno);
            $msgNo1 = imap_msgno($imap1, $message->uid);
            $this->assertEquals($message->msgno, $msgNo1);
            $this->assertEquals($message->uid, $uid1);

            $uid2 = imap2_uid($imap2, $message->msgno);
            $msgNo2 = imap2_msgno($imap2, $message->uid);
            $this->assertEquals($message->msgno, $msgNo2);
            $this->assertEquals($message->uid, $uid2);

            $this->assertEquals($uid1, $uid2);
            $this->assertEquals($msgNo1, $msgNo2);
        }

        imap_close($imap1);
        imap2_close($imap2);
    }

    public function testListScan()
    {
        $imap1 = imap_open($this->mailbox, $this->username, $this->password);

        $imap2 = imap2_open($this->mailbox, $this->username, $this->accessToken, OP_XOAUTH2);

        $a = imap_listscan($imap1, $this->mailbox, '*', ' ');

        var_dump($a, IMAP2_RETROFIT_MODE);
        $randomMailboxName1 = 'Mailbox ' . Functions::unique();
        $randomMailboxName2 = 'Mailbox ' . Functions::unique();

        $success1 = imap_create($imap1, $randomMailboxName1);
        var_dump(imap_last_error());
        var_dump($success1);
        die();
        $success2 = imap2_createmailbox($imap2, $randomMailboxName2);

        $this->assertEquals($success1, $success2);

        $success1 = imap_createmailbox($imap1, $randomMailboxName1);
        $success2 = imap2_createmailbox($imap2, $randomMailboxName2);

        $this->assertEquals($success1, $success2);

        imap_close($imap1);
        imap2_close($imap2);
    }

    public function testCreateMailbox()
    {
        $imap1 = imap_open($this->mailbox, $this->username, $this->password);
        $imap2 = imap2_open($this->mailbox, $this->username, $this->accessToken, OP_XOAUTH2);

        $randomMailboxName1 = 'Mailbox ' . Functions::unique();
        $randomMailboxName2 = 'Mailbox ' . Functions::unique();

        $success1 = imap_create($imap1, $randomMailboxName1);
        var_dump($randomMailboxName1, imap_last_error());
        var_dump($success1);
        die();
        $success2 = imap2_createmailbox($imap2, $randomMailboxName2);

        $this->assertEquals($success1, $success2);

        $success1 = imap_createmailbox($imap1, $randomMailboxName1);
        $success2 = imap2_createmailbox($imap2, $randomMailboxName2);

        $this->assertEquals($success1, $success2);

        imap_close($imap1);
        imap2_close($imap2);
    }

    public function testCopy()
    {
        $imap1 = imap_open($this->mailbox, $this->username, $this->password);
        $imap2 = imap2_open($this->mailbox, $this->username, $this->accessToken, OP_XOAUTH2);

        $randomMailboxName = 'Mailbox '.Functions::unique();
        $this->assertTrue(imap2_createmailbox($imap2, $randomMailboxName));

        $messageNums1 = '1:2';
        $messageNums2 = '3:4';
        $success1 = imap_mail_copy($imap1, $messageNums1, $randomMailboxName);
        $success2 = imap2_mail_copy($imap2, $messageNums2, $randomMailboxName);

        $this->assertEquals($success1, $success2);
        $this->assertTrue($success2);

        $status1 = imap_status($imap1, $this->mailbox.$randomMailboxName, SA_ALL);
        $status2 = imap2_status($imap2, $this->mailbox.$randomMailboxName, SA_ALL);

        unset($status1->flags);

        $this->assertEquals(4, $status2->messages);
        $this->assertEquals($status1, $status2);

        imap_close($imap1);
        imap2_close($imap2);
    }

    public function testMove()
    {
        $imap1 = imap_open($this->mailbox, $this->username, $this->password);
        $imap2 = imap2_open($this->mailbox, $this->username, $this->accessToken, OP_XOAUTH2);

        $initialStatus1 = imap_status($imap1, $this->mailbox, SA_ALL);
        $initialStatus2 = imap2_status($imap2, $this->mailbox, SA_ALL);

        $randomMailboxName = 'Mailbox '.Functions::unique();
        $this->assertTrue(imap2_createmailbox($imap2, $randomMailboxName));

        $messageNums1 = '1:2';
        $messageNums2 = '1:2';
        $success1 = imap_mail_move($imap1, $messageNums1, $randomMailboxName);
        #$success2 = imap2_mail_move($imap2, $messageNums2, $randomMailboxName);

        var_dump($success1, $success1);
        die();

        $this->assertEquals($success1, $success2);
        $this->assertTrue($success2);

        $status1 = imap_status($imap1, $this->mailbox.$randomMailboxName, SA_ALL);
        $status2 = imap2_status($imap2, $this->mailbox.$randomMailboxName, SA_ALL);

        $finalStatus1 = imap_status($imap1, $this->mailbox, SA_ALL);
        $finalStatus2 = imap2_status($imap2, $this->mailbox, SA_ALL);

        unset($status1->flags);
        unset($initialStatus1->flags);
        unset($finalStatus1->flags);

        $this->assertEquals(4, $status2->messages);
        $this->assertEquals($initialStatus2->messages - 4, $finalStatus2->messages);
        $this->assertEquals($status1, $status2);
        $this->assertEquals($initialStatus1, $initialStatus2);
        $this->assertEquals($finalStatus1, $finalStatus2);

        imap_close($imap1);
        imap2_close($imap2);
    }

}
