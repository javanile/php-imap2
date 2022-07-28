<?php

namespace Javanile\Imap2\Tests;

use PHPUnit\Framework\Error\Warning;

class ErrorsTest extends ImapTestCase
{
    public function testLastError1()
    {
        $this->expectException(Warning::class);
        $this->expectExceptionMessage('imap2_open(): Couldn\'t open stream ... in ' . __FILE__ . ' on line ' . (__LINE__ + 1));
        imap2_open('...', '...', '...', OP_XOAUTH2);
        $this->assertEquals('Can\'t open mailbox ...: no such mailbox', imap2_last_error());
    }

    public function testLastError2()
    {
        $this->expectException(Warning::class);
        $this->expectExceptionMessage('imap2_open(): Couldn\'t open stream {imap.gmail.com:993/imap/ssl} in '.__FILE__.' on line '.(__LINE__ + 1));
        imap2_open('{imap.gmail.com:993/imap/ssl}', 'wrong-username', 'wrong-password', OP_XOAUTH2);
        $this->assertEquals('Can not authenticate to IMAP server: [AUTHENTICATIONFAILED] Invalid credentials (Failure)', imap2_last_error());
    }

    public function testFetchBodyBadMessageNumber()
    {
        $this->expectException(Warning::class);
        $this->expectExceptionMessage('imap2_fetchbody(): Bad message number in '.__FILE__.' on line '.(__LINE__ + 2));
        $imap = imap2_open($this->mailbox, $this->username, $this->accessToken, OP_XOAUTH2);
        $body = imap2_fetchbody($imap, 9999, null);
        $this->assertFalse(imap2_last_error());
        $this->assertFalse($body);
    }
}
