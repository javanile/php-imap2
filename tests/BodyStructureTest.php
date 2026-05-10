<?php

namespace Javanile\Imap2\Tests;

use Javanile\Imap2\Connection;
use Javanile\Imap2\Functions;
use PHPUnit\Framework\Error\Warning;

class BodyStructureTest extends ImapTestCase
{
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
            #'embedded_email.eml',
            #'embedded_email_without_content_disposition.eml',
            #'four_nested_emails.eml'
            #'multiple_nested_attachments.eml'
            'email_with_image.eml'
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
            $structure2 = imap2_fetchstructure($imap2, 1);
            $structure1 = imap_fetchstructure($imap1, 1);
            $headerInfo2 = imap2_headerinfo($imap2, 1);
            $headerInfo1 = imap_headerinfo($imap1, 1);
            #file_put_contents('t1.json', json_encode($structure1, JSON_PRETTY_PRINT));
            #file_put_contents('t2.json', json_encode($structure2, JSON_PRETTY_PRINT));
            #die();
            $this->assertEquals($structure1, $structure2);
            $headerInfo1->Unseen = $headerInfo2->Unseen;
            $this->assertEquals($headerInfo1, $headerInfo2);
        }

        imap_close($imap1);
        imap2_close($imap2);
    }

    public function testBodyStruct()
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
            #'embedded_email.eml',
            #'embedded_email_without_content_disposition.eml',
            #'four_nested_emails.eml'
            #'multiple_nested_attachments.eml'
            'email_with_image.eml'
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
            foreach (['1', '2', '1.1', '1.2'] as $section) {
                $structure1 = imap_bodystruct($imap1, 1, $section);
                $structure2 = imap2_bodystruct($imap2, 1, $section);
                #file_put_contents('t1.json', json_encode($structure1, JSON_PRETTY_PRINT));
                #file_put_contents('t2.json', json_encode($structure2, JSON_PRETTY_PRINT));
                #die();
                $this->assertEquals($structure1, $structure2);
            }
        }

        imap_close($imap1);
        imap2_close($imap2);
    }
}
