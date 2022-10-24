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

        $boxes1 = imap_search($imap1, $this->mailbox, '*');
        $boxes2 = imap2_search($imap2, $this->mailbox, '*');

        imap_close($imap1);
        imap2_close($imap2);

        $this->assertEquals($boxes1, $boxes2);
    }
}
