<?php

namespace Javanile\Imap2\Tests;

use Javanile\Imap2\Connection;
use Javanile\Imap2\Polyfill;
use PHPUnit\Framework\Error\Warning;

class RetrofitTest extends ImapTestCase
{
    public function testRetrofitOpen()
    {
        $imap = imap2_open($this->mailbox, $this->username, $this->password);
        $this->assertTrue(is_resource($imap));

        imap2_close($imap);
    }
}
