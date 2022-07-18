<?php

namespace Javanile\Imap2\Tests;

use PHPUnit\Framework\Error\Warning;

class ErrorsTest extends ImapTestCase
{
    public function testLastError()
    {
        $this->expectException(Warning::class);
        $this->expectExceptionMessage('Warning: imap2_open(): Couldn\'t open stream ... in '.__FILE__.' on line '.(__LINE__ + 1));
        imap2_open('...', '...', '...', OP_XOAUTH2);
        $this->assertEquals('Can\'t open mailbox ...: no such mailbox', imap2_last_error());

        $this->expectException(Warning::class);
        $this->expectExceptionMessage('Warning: imap2_open(): Couldn\'t open stream {imap.gmail.com:993/imap/ssl} in '.__FILE__.' on line '.(__LINE__ + 1));
        imap2_open('{imap.gmail.com:993/imap/ssl}', 'wrong-username', 'wrong-password', OP_XOAUTH2);
        $this->assertEquals('Can not authenticate to IMAP server: [AUTHENTICATIONFAILED] Invalid credentials (Failure)', imap2_last_error());
    }
}
