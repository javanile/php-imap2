<?php

/*
 * This file is part of the PHP IMAP2 package.
 *
 * (c) Francesco Bianco <bianco@javanile.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Javanile\Imap2;

use Webklex\PHPIMAP\ClientManager;

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
    protected $port;
    protected $sslMode;
    protected $currentMailbox;
    protected $connected;
    protected $registry;

    /**
     *
     */
    public function __construct($mailbox, $user, $password, $flags = 0, $retries = 0, $options = [])
    {
        $this->user = $user;
        $this->password = $password;
        $this->flags = $flags;
        $this->retries = $retries;
        $this->options = $options;

        $this->openMailbox($mailbox);

        $clientManager = new ClientManager();

        if (defined('IMAP2_DEBUG') && IMAP2_DEBUG) {
            echo "\n\n";
            $clientManager->setConfig([
                'options' => [
                    'debug' => true
                ]
            ]);
        }

        $this->client = $clientManager->make([
            'host' => $this->host,
            'port' => $this->port,
            'protocol'  => 'imap',
            'encryption' => 'ssl',
            'validate_cert' => true,
            'username' => $user,
            'password' => $password,
            'authentication' => $this->flags & OP_XOAUTH2 ? 'oauth' : null,
        ]);
    }

    /**
     *
     * @param $mailbox
     *
     * @return void
     */
    public function openMailbox($mailbox)
    {
        $this->mailbox = $mailbox;

        $mailboxParts = Functions::parseMailboxString($mailbox);

        $this->host = Functions::getHostFromMailbox($mailboxParts);
        $this->port = @$mailboxParts['port'];
        $this->sslMode = Functions::getSslModeFromMailbox($mailboxParts);
        $this->currentMailbox = $mailboxParts['mailbox'];
    }

    /**
     * Open an IMAP stream to a mailbox.
     *
     * @param $mailbox
     * @param $user
     * @param $password
     * @param int $flags
     * @param int $retries
     * @param array $options
     *
     * @return void
     */
    public static function open($mailbox, $user, $password, $flags = 0, $retries = 0, $options = [])
    {
        $connection = new Connection($mailbox, $user, $password, $flags, $retries, $options);

        $success = $connection->connect();

        if (empty($success)) {
            Errors::appendErrorCanNotOpen($mailbox, $connection->getLastError());

            trigger_error(Errors::couldNotOpenStream($mailbox, debug_backtrace(), 1), E_USER_WARNING);

            return false;
        }

        return $connection;
    }

    public static function reopen($imap, $mailbox, $flags = 0, $retries = 0)
    {
        if (!is_a($imap, Connection::class)) {
            return Errors::invalidImapConnection(debug_backtrace(), 1, null);
        }

        $imap->openMailbox($mailbox);

        $success = $imap->connect();

        if (empty($success)) {
            trigger_error('imap2_reopen(): Couldn\'t re-open stream', E_USER_WARNING);

            return false;
        }

        $imap->selectMailbox();

        return true;
    }

    public static function ping($imap)
    {
        if (!is_a($imap, Connection::class)) {
            return Errors::invalidImapConnection(debug_backtrace(), 1, null);
        }

        $client = $imap->getClient();
        #$client->setDebug(true);
        $status = $client->status($imap->getMailboxName(), ['UIDNEXT']);

        return isset($status['UIDNEXT']) && $status['UIDNEXT'] > 0;
    }

    /**
     *
     */
    protected function connect()
    {
        $this->connected = false;
        $client = $this->getClient();
        #$client->setDebug(true);

        try {
            $client->connect();
        } catch (\Throwable $error) {
            return false;
        }

        if (empty($this->currentMailbox)) {
            $mailboxes = $this->client->connection->folders('', '*');
            if (isset($mailboxes['INBOX']) && $mailboxes['INBOX']) {
                $this->currentMailbox = 'INBOX';
                $this->mailbox .= 'INBOX';
            }
        }

        $this->rewriteMailbox();

        $this->connected = true;

        return $this;
    }

    /**
     *
     */
    protected function rewriteMailbox($forceMailbox = null)
    {
        $mailboxParts = Functions::parseMailboxString($this->mailbox);

        // '{imap.gmail.com:993/imap/notls/ssl/user="javanile.develop@gmail.com"}INBOX'
        $params = [];

        $params[] = 'imap';
        if ($this->sslMode == 'ssl') {
            $params[] = 'notls';
            $params[] = 'ssl';
        }
        $params[] = 'user="'.$this->user.'"';

        $mailboxName = $forceMailbox ? $forceMailbox : $mailboxParts['mailbox'];

        $updatedMailbox = '{'.$mailboxParts['host'].':'.$mailboxParts['port'].'/'.implode('/', $params).'}'.$mailboxName;

        $this->mailbox = $updatedMailbox;
    }

    /**
     *
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     *
     */
    public function getMailbox()
    {
        return $this->mailbox;
    }

    /**
     *
     */
    public function getMailboxName()
    {
        return $this->currentMailbox;
    }

    /**
     *
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     *
     */
    public function selectMailbox()
    {
        $success = $this->client->connection->selectFolder($this->currentMailbox);

        if (empty($success)) {
            $this->rewriteMailbox('<no_mailbox>');
        }
    }

    /**
     *
     */
    public static function close($imap, $flags = 0)
    {
        if (!is_a($imap, Connection::class)) {
            return Errors::invalidImapConnection(debug_backtrace(), 1, false);
        }

        $client = $imap->getClient();

        $client->disconnect();

        return true;
    }

    public function isConnected()
    {
        return boolval($this->connected);
    }

    public static function isValid($imap)
    {
        return is_a($imap, Connection::class) && $imap->isConnected();
    }

    public function setRegistryValue($space, $item, $key, $value)
    {
        if (empty($this->registry)) {
            $this->registry = [];
        }

        if (empty($this->registry[$space])) {
            $this->registry[$space] = [];
        }

        if (empty($this->registry[$space][$item])) {
            $this->registry[$space][$item] = [];
        }

        $this->registry[$space][$item][$key] = $value;
    }

    public function getRegistryValue($space, $item, $key)
    {
        if (isset($this->registry[$space][$item][$key])) {
            return $this->registry[$space][$item][$key];
        }

        return false;
    }

    public function getLastError()
    {
        $client = $this->getClient();

        return $client->error;
    }
}
