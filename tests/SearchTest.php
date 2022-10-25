<?php

namespace Javanile\Imap2\Tests;

use Javanile\Imap2\Connection;
use Javanile\Imap2\Functions;
use PHPUnit\Framework\Error\Warning;

class SearchTest extends ImapTestCase
{
    public function testSearch()
    {
        $imap1 = imap_open($this->mailbox, $this->username, $this->password);
        $imap2 = imap2_open($this->mailbox, $this->username, $this->accessToken, OP_XOAUTH2);

        $criteriaList = [
            'ALL',
            #'ANSWERED',
            #'BCC "string"',
            #'BEFORE "date"',
            #'BODY "string"',
            #'CC "string"',
            #'DELETED',
            #'FLAGGED',
            #'FROM "string"',
            #'KEYWORD "string"',
            #'NEW',
            #'OLD',
            #'ON "date"',
            #'RECENT',
            #'SEEN',
            #'SINCE "date"',
            #'SUBJECT "string"',
            #'TEXT "string"',
            #'TO "string"',
            #'UNANSWERED',
            #'UNDELETED',
            #'UNFLAGGED',
            #'UNKEYWORD "string"',
            #'UNSEEN',
        ];

        foreach ($criteriaList as $criteria) {
            $search1 = imap_search($imap1, $criteria);
            #file_put_contents('s1.json', json_encode($search1, JSON_PRETTY_PRINT));
            $search2 = imap2_search($imap2, $criteria);
            #file_put_contents('s2.json', json_encode($search2, JSON_PRETTY_PRINT));
            $this->assertEquals($search1, $search2);
        }

        $search1 = imap_search($imap1, 'ALL', SE_UID);
        $search2 = imap2_search($imap2, 'ALL', SE_UID);

        $this->assertEquals($search1, $search2);

        imap_close($imap1);
        imap2_close($imap2);
    }
}
