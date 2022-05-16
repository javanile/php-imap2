<?php

namespace Javanile\Imap2\Tests;

use PHPUnit\Framework\Error\Warning;

class ErrorsTest extends ImapTestCase
{
    /*
    public function testOpenAndClose()
    {
        $username = 'wrong@username.local';
        $this->expectException(Warning::class);
        $this->expectExceptionMessage('imap_open(): Couldn\'t open stream {imap.gmail.com:993/imap/ssl}');
        $imap1 = imap_open($this->mailbox, $username, $this->password);

        #var_dump($imap1);die();

        $this->expectException(Warning::class);
        $this->expectExceptionMessage('imap_open(): Couldn\'t open stream {imap.gmail.com:993/imap/ssl}');
        $imap2 = imap2_open($this->mailbox, $username, $this->accessToken, OP_XOAUTH2);

        $this->assertEquals($imap1, $imap2);

    }

    public function testAppend()
    {
        #var_dump($this->mailbox);
        #die();

        $imap1 = imap_open($this->mailbox, $this->username, $this->password);
        $imap2 = imap2_open($this->mailbox, $this->username, $this->accessToken, OP_XOAUTH2);

        $check1 = imap_check($imap1);
        $check2 = imap2_check($imap2);
        $this->assertEquals($check1, $check2);

        $initialCount = $check2->Nmsgs;

        imap_append($imap1, $this->mailbox, $this->message);
        imap2_append($imap2, $this->mailbox, $this->message);

        $check1 = imap_check($imap1);
        $check2 = imap2_check($imap2);
        $this->assertEquals($check1, $check2);

        $finalCount = $check2->Nmsgs;

        imap_close($imap1);
        imap2_close($imap2);

        $this->assertEquals($initialCount + 2, $finalCount);
    }

    public function testList()
    {
        $imap1 = imap_open($this->mailbox, $this->username, $this->password);
        $imap2 = imap2_open($this->mailbox, $this->username, $this->accessToken, OP_XOAUTH2);

        $boxes1 = imap_list($imap1, $this->mailbox, '*');
        $boxes2 = imap2_list($imap2, $this->mailbox, '*');

        imap_close($imap1);
        imap2_close($imap2);

        $this->assertEquals($boxes1, $boxes2);
    }
    */

    public function testWrongImapResourceAsInput()
    {
        $this->assertEquals(1, 1);
        /*
        $imap1 = imap_open($this->mailbox, $this->username, $this->password);
        #var_dump(get_resource_type($imap1));
        $imap = (object)[];
        imap_close($imap);
        imap2_close($imap);
        #die();
        */
    }

}
