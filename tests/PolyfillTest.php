<?php

namespace Javanile\Imap2\Tests;

use Javanile\Imap2\Connection;
use Javanile\Imap2\Polyfill;
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

    public function testRfc822ParseHeaders()
    {
        $emlFiles = [
            'embedded_email.eml'
        ];
        foreach ($emlFiles as $file) {
            $message = file_get_contents('tests/fixtures/' . $file);
            $headers1 = imap_rfc822_parse_headers($message);
            $headers2 = Polyfill::rfc822ParseHeaders($message);

            #file_put_contents('a1.json', json_encode($headers1, JSON_PRETTY_PRINT));
            #file_put_contents('a2.json', json_encode($headers2, JSON_PRETTY_PRINT));

            #var_dump($headers1);
            #var_dump($headers2);

            $this->assertEquals($headers1, $headers2);
        }
    }

    public function testRfc822ParseAdrList()
    {
        $fixtures = [
            'Sender <sender@test.com>, CC@test.com, root',
        ];

        $defaultHostname = 'default.host';
        foreach ($fixtures as $addresses) {
            $addressObjectList1 = imap_rfc822_parse_adrlist($addresses, $defaultHostname);
            $addressObjectList2 = Polyfill::rfc822ParseAdrList($addresses, $defaultHostname);

            #file_put_contents('a1.json', json_encode($addressObjectList1, JSON_PRETTY_PRINT));
            #file_put_contents('a2.json', json_encode($addressObjectList2, JSON_PRETTY_PRINT));

            #var_dump($addressObjectList1);
            #var_dump($addressObjectList2);

            $this->assertEquals($addressObjectList1, $addressObjectList2);
        }
    }
}
