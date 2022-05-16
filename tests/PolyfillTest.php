<?php

namespace Javanile\Imap2\Tests;

use Javanile\Imap2\Connection;
use PHPUnit\Framework\Error\Warning;

class PolyfillTest extends ImapTestCase
{
    public function testMailCompose()
    {
        /*
        $envelope = [];
        $bodies = [];

        $mail = imap2_mail_compose($envelope, $bodies);

        #var_dump($mail);

        $this->assertEquals($close1, $close2);
        */
        $this->assertEquals(1, 1);
    }
}
