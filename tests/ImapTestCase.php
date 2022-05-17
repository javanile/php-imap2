<?php

namespace Javanile\Imap2\Tests;

use PHPUnit\Framework\TestCase;

class ImapTestCase extends TestCase
{
    protected $mailbox;
    protected $username;
    protected $password;
    protected $accessToken;

    protected $altMailbox;
    protected $altUsername;
    protected $altPassword;
    protected $altAccessToken;

    protected $message;

    protected $errorMessage;
    protected $errorNumber;

    public function setUp()
    {
        $this->mailbox = getenv('IMAP_MAILBOX');
        $this->username = getenv('IMAP_USERNAME');
        $this->password = getenv('IMAP_PASSWORD');
        $this->accessToken = getenv('IMAP_ACCESS_TOKEN');

        $this->altMailbox = getenv('IMAP_ALT_MAILBOX');
        $this->altUsername = getenv('IMAP_ALT_USERNAME');
        $this->altPassword = getenv('IMAP_ALT_PASSWORD');
        $this->altAccessToken = getenv('IMAP_ALT_ACCESS_TOKEN');

        $this->message = file_get_contents('tests/fixtures/message.eml');
    }

    public function captureError()
    {
        $this->errorMessage = null;
        $this->errorNumber = null;
        $errorMessage =& $this->errorMessage;
        $errorNumber =& $this->errorNumber;
        \set_error_handler(static function ($nr, $message) use (&$errorMessage, &$errorNumber) {
            $errorMessage = $message;
            $errorNumber = $nr;
        });
    }

    public function retrieveError(/*&$errorNumber = 0*/)
    {
        \restore_error_handler();

        if (empty($this->errorMessage)) {
            return null;
        }

        /*$errorNumber = $this->errorNumber;*/

        return $this->errorMessage;
    }
}
