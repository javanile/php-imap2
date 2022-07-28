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

    public function testRetrofitAll()
    {
        $imap = imap_open($this->mailbox, $this->username, $this->password);

        foreach ($this->functions as $function) {
            $parameters = array_map(
                function ($parameter) use ($function) { return $parameter->name; },
                (new \ReflectionFunction('imap2_' . $function))->getParameters()
            );

            if ($parameters[0] != 'imap') {
                continue;
            }

            $parameters[0] = $imap;

            try {
                call_user_func_array('imap2_'.$function, $parameters);
            } catch (\Exception $error) {
                $this->assertContains('imap_'.$function.'()', $error->getMessage());
            }
        }

        imap_close($imap);
    }
}
