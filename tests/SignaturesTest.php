<?php

namespace Javanile\Imap2\Tests;

use Javanile\Imap2\Connection;
use PHPUnit\Framework\Error\Warning;

class SignaturesTest extends ImapTestCase
{
    public function testFunctions()
    {
        foreach ($this->functions as $function) {
            $function1 = new \ReflectionFunction('imap_'.$function);
            $function2 = new \ReflectionFunction('imap2_'.$function);
            $parameters1 = array_map(function($parameter) { return (array) $parameter; }, $function1->getParameters());
            $parameters2 = array_map(function($parameter) { return (array) $parameter; }, $function2->getParameters());

            foreach ($parameters2 as $index => $parameter) {
                $parameters1[$index]['name'] = $parameter['name'];
            }

            $this->assertEquals($parameters1, $parameters2);
        }
    }

    public function testInputOutput()
    {
        $inputs = json_decode(file_get_contents('tests/fixtures/inputs.json'), true);
        foreach ($this->functions as $function) {
            if (in_array($function, ['alerts', 'errors', 'last_error', 'timeout'])) {
                continue;
            }

            $parameters = $inputs[$function] ?? array_map(
                function($parameter) use ($function) { return $parameter->name; },
                (new \ReflectionFunction('imap2_'.$function))->getParameters()
            );

            $output1 = @call_user_func_array('imap_'.$function, $parameters);
            $output2 = @call_user_func_array('imap2_'.$function, $parameters);

            $this->assertEquals($output1, $output2, 'Problem with function imap_'.$function);
        }
    }
}
