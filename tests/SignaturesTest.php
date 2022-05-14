<?php

namespace Javanile\Imap2\Tests;

use Javanile\Imap2\Connection;
use PHPUnit\Framework\Error\Warning;

class SignaturesTest extends ImapTestCase
{
    protected $functions = [
        '8bit',
        'alerts',
        'append',
        'base64',
        'binary',
        'body',
        'bodystruct',
        'check',
        'clearflag_full',
        'close',
        'create',
        'createmailbox',
        'delete',
        'deletemailbox',
        'errors',
        'expunge',
        'fetch_overview',
        'fetchbody',
        'fetchheader',
        'fetchmime',
        'fetchstructure',
        'fetchtext',
        'gc',
        'get_quota',
        'get_quotaroot',
        'getacl',
        'getmailboxes',
        'getsubscribed',
        'header',
        'headerinfo',
        'headers',
        'last_error',
        'list',
        'listmailbox',
        'listscan',
        'listsubscribed',
        'lsub',
        'mail_compose',
        'mail_copy',
        'mail_move',
        'mail',
        'mailboxmsginfo',
        'mime_header_decode',
        'msgno',
        'mutf7_to_utf8',
        'num_msg',
        'num_recent',
        'open',
        'ping',
        'qprint',
        'rename',
        'renamemailbox',
        'reopen',
        'rfc822_parse_adrlist',
        'rfc822_parse_headers',
        'rfc822_write_address',
        'savebody',
        'scan',
        'scanmailbox',
        'search',
        'set_quota',
        'setacl',
        'setflag_full',
        'sort',
        'status',
        'subscribe',
        'thread',
        'timeout',
        'uid',
        'undelete',
        'unsubscribe',
        'utf7_decode',
        'utf7_encode',
        'utf8_to_mutf7',
        'utf8',
    ];

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
            $parameters = $inputs[$function] ?? array_map(
                function($parameter) use ($function) { return $parameter->name; },
                (new \ReflectionFunction('imap2_'.$function))->getParameters()
            );

            var_dump($function);
            $output1 = call_user_func_array('imap_'.$function, $parameters);
            $output2 = call_user_func_array('imap2_'.$function, $parameters);

            $this->assertEquals($output1, $output2);
        }
    }
}
