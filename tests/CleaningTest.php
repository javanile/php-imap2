<?php

namespace Javanile\Imap2\Tests;

use Javanile\Imap2\Functions;

class CleaningTest extends ImapTestCase
{
    public function testRemoveUnusedMailbox()
    {
        $imap = imap2_open($this->mailbox, $this->username, $this->accessToken, OP_XOAUTH2);

        $deleteRule = '/^(\d+|test_|mailbox_|Mailbox |Mailbox-)/';

        $mailboxes = imap2_getmailboxes($imap, $this->mailbox, '*');
        foreach ($mailboxes as $mailbox) {
            if (preg_match($deleteRule, Functions::getMailboxName($mailbox->name))) {
                imap2_deletemailbox($imap, $mailbox->name);
            }
        }

        $mailboxes = imap2_getmailboxes($imap, $this->mailbox, '*');
        foreach ($mailboxes as $mailbox) {
            $this->assertEquals(0, preg_match($deleteRule, Functions::getMailboxName($mailbox->name)));
        }

        $this->assertLessThan(10, count($mailboxes));

        imap2_close($imap);
    }

    public function testFillInboxWithTestingMessages()
    {
        $imap = imap2_open($this->mailbox, $this->username, $this->accessToken, OP_XOAUTH2);

        $check = imap2_check($imap);
        if ($check->Nmsgs > 0) {
            imap2_delete($imap, '1:*');
            imap2_expunge($imap);
        }

        $emlFiles = [
            'plain_only.eml',
            'embedded_email.eml',
            'references.eml',
            'email_with_image.eml',
            'embedded_email_without_content_disposition.eml',
            'four_nested_emails.eml'
        ];

        foreach ($emlFiles as $file) {
            $this->assertTrue(imap2_append($imap, $this->mailbox, file_get_contents('tests/fixtures/' . $file)));
        }

        $check = imap2_check($imap);

        $this->assertEquals(count($emlFiles), $check->Nmsgs);
    }
}
