<?php

namespace Javanile\Imap2\Tests;

use Javanile\Imap2\Functions;

class CleaningTest extends ImapTestCase
{
    public function testRemoveUnusedMailbox()
    {
        $imap = imap2_open($this->mailbox, $this->username, $this->accessToken, OP_XOAUTH2);

        $deleteRule = '/^(\d+|test_|mailbox_)/';

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
}
