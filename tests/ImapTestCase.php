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
