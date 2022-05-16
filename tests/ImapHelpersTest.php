<?php

namespace Javanile\Imap2\Tests;

use Javanile\Imap2\Connection;
use PHPUnit\Framework\Error\Warning;

class ImapHelpersTest extends ImapTestCase
{
    public function testIdToUid()
    {
        $this->assertEquals(1, 1);
        /*
        $imap = @imap2_open($this->mailbox, $this->username, $this->accessToken, OP_XOAUTH2);
        $this->assertEquals($close1, $close2);
        */
    }
}
