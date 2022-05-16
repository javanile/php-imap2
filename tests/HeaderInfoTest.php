<?php

namespace Javanile\Imap2\Tests;

use Javanile\Imap2\Connection;
use Javanile\Imap2\HeaderInfo;
use PHPUnit\Framework\Error\Warning;

class HeaderInfoTest extends ImapTestCase
{
    public function testSanitizeAddress()
    {
        $inputs = [
            '"TeamViewer Sign In Confirmation" <SignIn-noreply@teamviewer.com>' => 'TeamViewer Sign In Confirmation <SignIn-noreply@teamviewer.com>',
            '"Aruba.it" <newsletter@staff.aruba.it>' => '"Aruba.it" <newsletter@staff.aruba.it>',
        ];

        foreach ($inputs as $input => $output) {
            $this->assertEquals($output, HeaderInfo::sanitizeAddress($input, 'localhost'));
        }
    }
}
