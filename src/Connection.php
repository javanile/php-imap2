<?php

/*
 * This file is part of the PHP Input package.
 *
 * (c) Francesco Bianco <bianco@javanile.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Javanile\Imap2;

use Javanile\Imap2\ImapClient;

class Connection
{
    protected $mailbox;
    protected $user;
    protected $password;
    protected $flags;
    protected $retries;
    protected $options;
    protected $client;
    protected $host;

    /**
     *
     */
    public function __construct($mailbox, $user, $password, $flags = 0, $retries = 0, $options = [])
    {
        $this->mailbox = $mailbox;
        $this->user = $user;
        $this->password = $password;
        $this->flags = $flags;
        $this->retries = $retries;
        $this->options = $options;

        $mailboxParts = Functions::parseMailboxString($mailbox);

        var_dump($mailboxParts);

        $this->host = $mailboxParts->host;

        $this->client = new ImapClient();
    }

    /**
     * Extract input value using input name from the context otherwise get back a default value.
     *
     * @param $inputName
     * @param $defaultValue
     * @return void
     */
    public static function open($mailbox, $user, $password, $flags = 0, $retries = 0, $options = [])
    {
        if ($flags & OP_XOAUTH2 || !function_exists('imap_open')) {
            $connection = new Connection($mailbox, $user, $password, $flags, $retries, $options);

            return $connection->connect();
        }

        return imap_open($mailbox, $user, $password, $flags, $retries, $options);
    }

    /**
     *
     */
    public function connect()
    {

        $this->client->connect($this->host);

        return $this;
    }
}
