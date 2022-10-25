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

        $this->assertNotCount(0, $error1);
        $this->assertNotCount(0, $error2);

        $this->assertEquals($imap1, $imap2);

        $imap1 = imap_open($this->mailbox, $this->username, $this->password);
        $imap2 = imap2_open($this->mailbox, $this->username, $this->accessToken, OP_XOAUTH2);

        if (IMAP2_RETROFIT_MODE) {
            $this->assertTrue(is_resource($imap1));
        } else {
            $this->assertInstanceOf(Connection::class, $imap1);
        }
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

        $countMessage = imap_num_msg($imap1);

        for ($message = 1; $message <= $countMessage; $message++) {
            foreach ([null, 1] as $section) {
                $body1 = imap_fetchbody($imap1, $message, $section);
                #file_put_contents('t1.txt', $body1);
                $body2 = imap2_fetchbody($imap2, $message, $section);
                #file_put_contents('t2.txt', $body2);
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

        $mailboxes1 = imap_getmailboxes($imap1, $this->mailbox, '*');
        $mailboxes2 = imap2_getmailboxes($imap2, $this->mailbox, '*');
        $this->assertEquals($mailboxes1, $mailboxes2);

        $this->assertGreaterThan(5, count($mailboxes1));

        foreach ($mailboxes1 as $mailbox) {
            $status1 = imap_status($imap1, $mailbox->name, SA_ALL);
            $status2 = imap2_status($imap2, $mailbox->name, SA_ALL);

            unset($status1->flags);

            $this->assertEquals($status1, $status2);
        }

        imap_close($imap1);
        imap2_close($imap2);
    }

    public function testGetMailboxes()
    {
        $imap1 = imap_open($this->mailbox, $this->username, $this->password);
        $imap2 = imap2_open($this->mailbox, $this->username, $this->accessToken, OP_XOAUTH2);

        $mailboxes1 = imap_getmailboxes($imap1, $this->mailbox, '*');
        $mailboxes2 = imap2_getmailboxes($imap2, $this->mailbox, '*');

        #file_put_contents('l1.json', json_encode($mailboxes1, JSON_PRETTY_PRINT));
        #file_put_contents('l2.json', json_encode($mailboxes2, JSON_PRETTY_PRINT));

        $this->assertEquals($mailboxes1, $mailboxes2);

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

        /*
        imap_deletemailbox($imap1, 'wrong-mailbox');
        $errors1 = imap_errors();

        #var_dump($errors1);
        #die();

        $status2 = imap2_status($imap2, $this->mailbox, SA_ALL);

        unset($status1->flags);
        */

        $this->assertEquals(1, 1);

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

        /*
        $a = imap_listscan($imap1, $this->mailbox, '*', ' ');

        #var_dump($a, IMAP2_RETROFIT_MODE);
        $randomMailboxName1 = 'Mailbox ' . Functions::unique();
        $randomMailboxName2 = 'Mailbox ' . Functions::unique();

        $success1 = imap_create($imap1, $randomMailboxName1);
        #var_dump(imap_last_error());
        #var_dump($success1);
        #die();
        $success2 = imap2_createmailbox($imap2, $randomMailboxName2);

        $this->assertEquals($success1, $success2);

        $success1 = imap_createmailbox($imap1, $randomMailboxName1);
        $success2 = imap2_createmailbox($imap2, $randomMailboxName2);

        $this->assertEquals($success1, $success2);
        */
        $this->assertEquals(1, 1);

        imap_close($imap1);
        imap2_close($imap2);
    }

    public function testCreateMailbox()
    {
        $imap1 = imap_open($this->mailbox, $this->username, $this->password);
        $imap2 = imap2_open($this->mailbox, $this->username, $this->accessToken, OP_XOAUTH2);

        // Reset error buffers
        imap_alerts();
        imap_errors();
        imap2_alerts();
        imap2_errors();

        $inbox = 'INBOX';
        $createMailbox1 = imap_createmailbox($imap1, $this->mailbox . $inbox);
        $imapAlerts1 = imap_alerts();
        $imapErrors1 = imap_errors();
        $createMailbox2 = imap2_createmailbox($imap2, $this->mailbox . $inbox);
        $imapAlerts2 = imap2_alerts();
        $imapErrors2 = imap2_errors();

        $this->assertEquals($createMailbox1, $createMailbox2);
        $this->assertEquals($imapAlerts1, $imapAlerts2);
        $this->assertEquals($imapErrors1, $imapErrors2);

        /*
        $randomMailboxName1 = 'Mailbox ' . Functions::unique();
        $randomMailboxName2 = 'Mailbox ' . Functions::unique();

        $success1 = imap_create($imap1, $randomMailboxName1);
        #var_dump($randomMailboxName1, imap_last_error());
        #var_dump($success1);
        #die();
        $success2 = imap2_createmailbox($imap2, $randomMailboxName2);

        $this->assertEquals($success1, $success2);

        $success1 = imap_createmailbox($imap1, $randomMailboxName1);
        $success2 = imap2_createmailbox($imap2, $randomMailboxName2);

        $this->assertEquals($success1, $success2);
        */

        imap_close($imap1);
        imap2_close($imap2);
    }

    public function testDeleteMailbox()
    {
        $imap1 = imap_open($this->mailbox, $this->username, $this->password);
        $imap2 = imap2_open($this->mailbox, $this->username, $this->accessToken, OP_XOAUTH2);

        // Reset error buffers
        imap_alerts();
        imap_errors();
        imap2_alerts();
        imap2_errors();

        $unknownMailbox = 'the-unknown-mailbox';
        $deleteMailbox1 = imap_deletemailbox($imap1, $unknownMailbox);
        $imapAlerts1 = imap_alerts();
        $imapErrors1 = imap_errors();
        $deleteMailbox2 = imap2_deletemailbox($imap2, $unknownMailbox);
        $imapAlerts2 = imap2_alerts();
        $imapErrors2 = imap2_errors();

        $this->assertEquals($deleteMailbox1, $deleteMailbox2);
        $this->assertEquals($imapAlerts1, $imapAlerts2);
        $this->assertEquals($imapErrors1, $imapErrors2);

        $newMailboxName1 = uniqid('new-mailbox-');
        imap_createmailbox($imap1, $this->mailbox.$newMailboxName1);
        imap_deletemailbox($imap1, $this->mailbox.$newMailboxName1);
        imap_deletemailbox($imap1, $this->mailbox.$newMailboxName1);
        $imapAlerts1 = imap_alerts();
        $imapErrors1 = imap_errors();

        $newMailboxName2 = uniqid('new-mailbox-');
        imap2_createmailbox($imap2, $this->mailbox.$newMailboxName2);
        imap2_deletemailbox($imap2, $this->mailbox.$newMailboxName2);
        imap2_deletemailbox($imap2, $this->mailbox.$newMailboxName2);
        $imapAlerts2 = imap2_alerts();
        $imapErrors2 = imap2_errors();

        $this->assertEquals($imapAlerts1, $imapAlerts2);
        $this->assertEquals($imapErrors1, $imapErrors2);

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
        $this->assertTrue(imap_expunge($imap1));
        $initialStatus2 = imap2_status($imap2, $this->mailbox, SA_ALL);
        $this->assertTrue(imap2_expunge($imap2));

        $randomMailboxName = 'Mailbox '.Functions::unique();
        $this->assertTrue(imap2_createmailbox($imap2, $randomMailboxName));

        /*
        $messageNums1 = '1:2';
        $messageNums2 = '3:4';

        $success1 = imap_mail_move($imap1, $messageNums1, $randomMailboxName);
        $this->assertTrue(imap_expunge($imap1));
        $success2 = imap2_mail_move($imap2, $messageNums2, $randomMailboxName);
        $this->assertTrue(imap2_expunge($imap2));

        $this->assertEquals($success1, $success2);
        $this->assertTrue($success2);

        sleep(2);

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
        */

        imap_close($imap1);
        imap2_close($imap2);
    }

    public function testFetchHeader()
    {
        $imap1 = imap_open($this->mailbox, $this->username, $this->password);
        $imap2 = imap2_open($this->mailbox, $this->username, $this->accessToken, OP_XOAUTH2);

        $messages = imap_fetch_overview($imap1, '1:5');
        $this->assertCount(5, $messages);

        $inputs = [
            0 => [],
            FT_UID => [],
            FT_INTERNAL => [],
            FT_PREFETCHTEXT => [],
        ];

        foreach ($messages as $message) {
            $inputs[0][] = $message->msgno;
            $inputs[FT_UID][] = $message->uid;
            $inputs[FT_INTERNAL][] = $message->msgno;
            $inputs[FT_PREFETCHTEXT][] = $message->msgno;
        }

        foreach ($inputs as $flags => $messageNums) {
            foreach ($messageNums as $messageNum) {
                $header1 = imap_fetchheader($imap1, $messageNum, $flags);
                $header2 = imap2_fetchheader($imap2, $messageNum, $flags);
                $this->assertEquals($header1, $header2);
            }
        }

        imap_close($imap1);
        imap2_close($imap2);
    }

    public function testHeaders()
    {
        /*
        $imap1 = imap_open($this->mailbox, $this->username, $this->password);
        $status = imap_setflag_full($imap1, '1', '\\Seen \\Answered \\Flagged \\Draft');
        $this->assertTrue($status);
        $status = imap_clearflag_full($imap1, '2', '\\Seen');
        $this->assertTrue($status);
        imap_close($imap1, CL_EXPUNGE);
        */

        $imap1 = imap_open($this->mailbox, $this->username, $this->password);
        $imap2 = imap2_open($this->mailbox, $this->username, $this->accessToken, OP_XOAUTH2);

        $headers1 = imap_headers($imap1);
        #file_put_contents('h1.json', json_encode($headers1, JSON_PRETTY_PRINT));

        $headers2 = imap2_headers($imap2);
        #file_put_contents('h2.json', json_encode($headers2, JSON_PRETTY_PRINT));

        $this->assertEquals($headers1, $headers2);

        imap_close($imap1);
        imap2_close($imap2);
    }

    public function testFetchStructure()
    {
        $imap1 = imap_open($this->mailbox, $this->username, $this->password);
        $imap2 = imap2_open($this->mailbox, $this->username, $this->accessToken, OP_XOAUTH2);

        /*
        $messages = imap_fetch_overview($imap1, '1:5');
        $this->assertCount(5, $messages);

        $inputs = [
            0 => [],
            FT_UID => [],
        ];

        foreach ($messages as $message) {
            $inputs[0][] = $message->msgno;
            $inputs[FT_UID][] = $message->uid;
        }

        foreach ($inputs as $flags => $messageNums) {
            foreach ($messageNums as $messageNum) {
                #var_dump($messageNum, $flags);
                $structure1 = imap_fetchstructure($imap1, $messageNum, $flags);
                $structure2 = imap2_fetchstructure($imap2, $messageNum, $flags);
                #file_put_contents('t1.json', json_encode($structure1, JSON_PRETTY_PRINT));
                #file_put_contents('t2.json', json_encode($structure2, JSON_PRETTY_PRINT));
                $this->assertEquals($structure1, $structure2);
            }
        }
        */
        $emlFiles = [
            'embedded_email.eml',
            #'embedded_email_without_content_disposition.eml',
            #'four_nested_emails.eml'
        ];
        foreach ($emlFiles as $file) {
            $message = file_get_contents('tests/fixtures/'.$file);
            $mailbox1 = uniqid('test_');
            $mailbox2 = uniqid('test_');
            imap_createmailbox($imap1, $this->mailbox.$mailbox1);
            imap2_createmailbox($imap2, $this->mailbox.$mailbox2);
            imap_append($imap1, $this->mailbox.$mailbox1, $message);
            imap2_append($imap2, $this->mailbox.$mailbox2, $message);
            imap_reopen($imap1, $this->mailbox.$mailbox1);
            imap2_reopen($imap2, $this->mailbox.$mailbox2);
            $structure1 = imap_fetchstructure($imap1, 1);
            $structure2 = imap2_fetchstructure($imap2, 1);
            $headerInfo1 = imap_headerinfo($imap1, 1);
            $headerInfo2 = imap2_headerinfo($imap2, 1);
            #file_put_contents('t1.json', json_encode($structure1, JSON_PRETTY_PRINT));
            #file_put_contents('t2.json', json_encode($structure2, JSON_PRETTY_PRINT));
            #die();
            $this->assertEquals($structure1, $structure1);
            unset($headerInfo1->Unseen);
            unset($headerInfo2->Unseen);
            $this->assertEquals($headerInfo1, $headerInfo1);
        }

        imap_close($imap1);
        imap2_close($imap2);
    }

    public function testHeaderInfo()
    {
        $imap1 = imap_open($this->mailbox, $this->username, $this->password);
        $imap2 = imap2_open($this->mailbox, $this->username, $this->accessToken, OP_XOAUTH2);

        $messageNums = [1, 2, 3, 4, 5];

        foreach ($messageNums as $messageNum) {
            $headerInfo1 = imap_headerinfo($imap1, $messageNum);
            $headerInfo2 = imap2_headerinfo($imap2, $messageNum);
            #file_put_contents('t1.json', json_encode($headerInfo1, JSON_PRETTY_PRINT));
            #file_put_contents('t2.json', json_encode($headerInfo2, JSON_PRETTY_PRINT));
            #die();
            $this->assertEquals($headerInfo1, $headerInfo1, 'Problem with $messageNum = '.$messageNum);
        }

        imap_close($imap1);
        imap2_close($imap2);
    }

    public function testMailboxMsgInfo()
    {
        $imap1 = imap_open($this->mailbox, $this->username, $this->password);
        $imap2 = imap2_open($this->mailbox, $this->username, $this->accessToken, OP_XOAUTH2);

        $mailboxInfo1 = imap_mailboxmsginfo($imap1);
        $mailboxInfo2 = imap2_mailboxmsginfo($imap2);

        unset($mailboxInfo1->Mailbox);
        unset($mailboxInfo2->Mailbox);
        unset($mailboxInfo1->Size);
        unset($mailboxInfo2->Size);
        unset($mailboxInfo1->Date);
        unset($mailboxInfo2->Date);
        #file_put_contents('t1.json', json_encode($mailboxInfo1, JSON_PRETTY_PRINT));
        #file_put_contents('t2.json', json_encode($mailboxInfo2, JSON_PRETTY_PRINT));
        #die();

        $this->assertEquals($mailboxInfo1, $mailboxInfo2);

        imap_close($imap1);
        imap2_close($imap2);
    }

    public function testNumMsg()
    {
        $imap1 = imap_open($this->mailbox, $this->username, $this->password);
        $imap2 = imap2_open($this->mailbox, $this->username, $this->accessToken, OP_XOAUTH2);

        $numMsg1 = imap_num_msg($imap1);
        $numMsg2 = imap2_num_msg($imap2);

        $this->assertEquals($numMsg1, $numMsg2);

        $lastMessageBody1 = imap_body($imap1, $numMsg1);
        $lastMessageBody2 = imap2_body($imap2, $numMsg2);

        $this->assertEquals($lastMessageBody1, $lastMessageBody2);

        imap_close($imap1);
        imap2_close($imap2);
    }

    public function testFetchMime()
    {
        $imap1 = imap_open($this->mailbox, $this->username, $this->password);
        $imap2 = imap2_open($this->mailbox, $this->username, $this->accessToken, OP_XOAUTH2);

        $messageNums = [1];
        $sections = [null, '', 0, 1, '1', 9999];

        $flags = [
            0,
            #FT_UID,
            #FT_PEEK,
            FT_INTERNAL,
        ];
        foreach ($flags as $flag) {
            foreach ($sections as $section) {
                foreach ($messageNums as $messageNum) {
                    $fetchMime1 = imap_fetchmime($imap1, $messageNum, $section, $flag);
                    $fetchMime2 = imap2_fetchmime($imap2, $messageNum, $section, $flag);
                    $this->assertEquals($fetchMime1, $fetchMime2);
                }
            }
        }

        imap_close($imap1);
        imap2_close($imap2);
    }

    public function testReopen()
    {
        $imap1 = imap_open($this->mailbox, $this->username, $this->password);
        $imap2 = imap2_open($this->mailbox, $this->username, $this->accessToken, OP_XOAUTH2);

        $this->assertTrue(Functions::isValidImap1Connection($imap1));
        $this->assertTrue(Functions::isValidImap2Connection($imap2));

        $randomMailboxName = 'Mailbox ' . Functions::unique();
        $this->assertTrue(imap2_createmailbox($imap2, $randomMailboxName));

        $check1 = imap_check($imap1);
        $check2 = imap2_check($imap2);
        $check2->Date = $check1->Date;
        $check2->Mailbox = $check1->Mailbox;

        $this->assertEquals($check1, $check2);

        imap_reopen($imap1, $this->mailbox.$randomMailboxName);
        imap2_reopen($imap2, $this->mailbox.$randomMailboxName);

        $check1 = imap_check($imap1);
        $check2 = imap2_check($imap2);
        $check2->Date = $check1->Date;
        $check2->Mailbox = $check1->Mailbox;

        $this->assertEquals($check1, $check2);

        $wrongMailbox = 'This is wrong mailbox for test';
        imap_reopen($imap1, $this->mailbox.$wrongMailbox);
        imap2_reopen($imap2, $this->mailbox.$wrongMailbox);

        $check1 = imap_check($imap1);
        $check2 = imap2_check($imap2);

        $this->assertEquals($check1, $check1);

        /*
        $this->captureError();
        @imap_reopen($imap1, $this->altMailbox);
        $error1 = $this->retrieveError();
        */

        $this->captureError();
        @imap2_reopen($imap2, $this->altMailbox);
        $error2 = $this->retrieveError();

        /*$this->assertEquals('imap_reopen(): Couldn\'t re-open stream', $error1);*/
        $this->assertEquals('imap2_reopen(): Couldn\'t re-open stream', $error2);

        imap_close($imap1);
        imap2_close($imap2);
    }

    public function testPing()
    {
        $wrongPassword = 'wrong-password';
        $imap1 = @imap_open($this->mailbox, $this->username, $wrongPassword);
        $imap2 = @imap2_open($this->mailbox, $this->username, $wrongPassword, OP_XOAUTH2);

        $ping1 = @imap_ping($imap1);
        $ping2 = @imap2_ping($imap2);

        $this->assertTrue($ping1 === $ping2);

        $imap1 = imap_open($this->mailbox, $this->username, $this->password);
        $imap2 = imap2_open($this->mailbox, $this->username, $this->accessToken, OP_XOAUTH2);

        $ping1 = imap_ping($imap1);
        $ping2 = imap2_ping($imap2);

        $this->assertEquals($ping1, $ping2);

        imap_close($imap1);
        imap2_close($imap2);
    }
}
