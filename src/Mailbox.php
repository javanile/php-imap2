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

class Mailbox
{
    public static function check($imap)
    {
        if (is_a($imap, Connection::class)) {
            $imap->openMailbox();

            $client = $imap->getClient();
            $status = $client->status($imap->getMailboxName(), ['MESSAGES', 'RECENT']);

            return (object) [
                'Date' => date('Y-m-d H:i:s'),
                'Driver' => 'imap',
                'Mailbox' => $imap->getMailbox(),
                'Nmsgs' => $status['MESSAGES'],
                'Recent' => $status['RECENT'],
            ];

        } elseif (IMAP2_RETROFIT_MODE && is_resource($imap) && get_resource_type($imap) == 'imap') {
            return imap_check($imap);
        }

        trigger_error(Errors::invalidImapConnection(debug_backtrace(), 1), E_USER_WARNING);

        return false;
    }

    public static function numMsg($imap)
    {
        if (is_a($imap, Connection::class)) {
            $client = $imap->getClient();
            $imap->openMailbox();

            return (object) [
                'Driver' => 'imap',
                'Mailbox' => $imap->getMailbox(),
                'Nmsgs' => $client->data['EXISTS'],
                'Recent' => $client->data['RECENT'],
            ];
        }

        return imap_check($imap);
    }

    public static function numRecent($imap)
    {
        if (is_a($imap, Connection::class)) {
            $client = $imap->getClient();
            $imap->openMailbox();

            return (object) [
                'Driver' => 'imap',
                'Mailbox' => $imap->getMailbox(),
                'Nmsgs' => $client->data['EXISTS'],
                'Recent' => $client->data['RECENT'],
            ];
        }

        return imap_check($imap);
    }

    public static function status($imap, $mailbox, $flags)
    {
        if (is_a($imap, Connection::class)) {
            $client = $imap->getClient();
            $mailboxParts = explode('}', $mailbox);
            $mailboxName = $mailboxParts[2] ?? 'INBOX';
            $items = [];

            $statusKeys = [
                'MESSAGES' => 'messages',
                'UNSEEN' => 'unseen',
                'RECENT' => 'recent',
                'UIDNEXT' => 'uidnext',
                'UIDVALIDITY' => 'uidvalidity',
            ];

            if ($flags & SA_MESSAGES || $flags & SA_ALL) {
                $items[] = 'MESSAGES';
            }
            if ($flags & SA_RECENT || $flags & SA_ALL) {
                $items[] = 'RECENT';
            }
            if ($flags & SA_UNSEEN || $flags & SA_ALL) {
                $items[] = 'UNSEEN';
            }
            if ($flags & SA_UIDNEXT || $flags & SA_ALL) {
                $items[] = 'UIDNEXT';
            }
            if ($flags & SA_UIDVALIDITY || $flags & SA_ALL) {
                $items[] = 'UIDVALIDITY';
            }

            $status = $client->status($mailboxName, $items);

            $returnStatus = [];
            foreach ($status as $key => $value) {
                $returnStatus[$statusKeys[$key]] = is_numeric($value) ? intval($value) : $value;
            }

            return (object) $returnStatus;
        }

        return imap_status($imap, $mailbox, $flags);
    }

    public static function mailboxMsgInfo($imap, $mailbox, $flags)
    {
        if (is_a($imap, Connection::class)) {
            $client = $imap->getClient();
            $mailboxParts = explode('}', $mailbox);
            $mailboxName = $mailboxParts[2] ?? 'INBOX';
            $items = [];

            $statusKeys = [
                'MESSAGES' => 'messages',
                'UNSEEN' => 'unseen',
                'RECENT' => 'recent',
                'UIDNEXT' => 'uidnext',
                'UIDVALIDITY' => 'uidvalidity',
            ];

            if ($flags & SA_MESSAGES || $flags & SA_ALL) {
                $items[] = 'MESSAGES';
            }
            if ($flags & SA_RECENT || $flags & SA_ALL) {
                $items[] = 'RECENT';
            }
            if ($flags & SA_UNSEEN || $flags & SA_ALL) {
                $items[] = 'UNSEEN';
            }
            if ($flags & SA_UIDNEXT || $flags & SA_ALL) {
                $items[] = 'UIDNEXT';
            }
            if ($flags & SA_UIDVALIDITY || $flags & SA_ALL) {
                $items[] = 'UIDVALIDITY';
            }

            $status = $client->status($mailboxName, $items);

            $returnStatus = [];
            foreach ($status as $key => $value) {
                $returnStatus[$statusKeys[$key]] = is_numeric($value) ? intval($value) : $value;
            }

            return (object) $returnStatus;
        }

        return imap_status($imap, $mailbox, $flags);
    }

    public static function list($imap, $reference, $pattern)
    {
        if (is_a($imap, Connection::class)) {
            $referenceParts = explode('}', $reference);
            $client = $imap->getClient();
            $return = [];
            $mailboxes = $client->listMailboxes($referenceParts[1], $pattern);
            foreach ($mailboxes as $mailbox) {
                if (in_array('\\Noselect', $client->data['LIST'][$mailbox])) {
                    continue;
                }
                $return[] = $referenceParts[0].'}'.$mailbox;
            }

            return $return;
        }

        return imap_list($imap, $reference, $pattern);
    }

    public static function listScan($imap, $reference, $pattern)
    {
        if (is_a($imap, Connection::class)) {
            $referenceParts = explode('}', $reference);
            $client = $imap->getClient();
            $return = [];
            $mailboxes = $client->listMailboxes($referenceParts[1], $pattern);
            foreach ($mailboxes as $mailbox) {
                if (in_array('\\Noselect', $client->data['LIST'][$mailbox])) {
                    continue;
                }
                $return[] = $referenceParts[0].'}'.$mailbox;
            }

            return $return;
        }

        return imap_list($imap, $reference, $pattern);
    }

    public static function getMailboxes($imap, $reference, $pattern)
    {
        if (is_a($imap, Connection::class)) {
            $referenceParts = explode('}', $reference);
            $client = $imap->getClient();
            $return = [];
            $mailboxes = $client->listMailboxes($referenceParts[1], $pattern);
            foreach ($mailboxes as $mailbox) {
                if (in_array('\\Noselect', $client->data['LIST'][$mailbox])) {
                    continue;
                }
                $return[] = $referenceParts[0].'}'.$mailbox;
            }

            return $return;
        }

        return imap_getmailboxes($imap, $reference, $pattern);
    }

    public static function createMailbox($imap, $mailbox)
    {
        if (is_a($imap, Connection::class)) {
            $client = $imap->getClient();
            $client->setDebug(true);

            return $client->createFolder($mailbox);
        }

        return imap_createmailbox($imap, $mailbox);
    }

    public static function renameMailbox($imap, $from, $to)
    {
        if (is_a($imap, Connection::class)) {
            $client = $imap->getClient();

            return $client->createFolder($mailbox);
        }

        return imap_createmailbox($imap, $mailbox);
    }

    public static function deleteMailbox($imap, $mailbox)
    {
        if (is_a($imap, Connection::class)) {
            $client = $imap->getClient();

            return $client->deleteFolder($mailbox);
        }

        return imap_deletemailbox($imap, $mailbox);
    }

    /**
     * Append a string message to a specified mailbox.
     *
     * @param $imap
     * @param $folder
     * @param $message
     * @param $options
     * @param $internalDate
     *
     * @return bool
     */
    public static function append($imap, $folder, $message, $options = null, $internalDate = null)
    {
        if (is_a($imap, Connection::class)) {
            $folderParts = explode('}', $folder);
            $client = $imap->getClient();
            $client->setDebug(true);
            $mailbox = empty($folderParts[1]) ? 'INBOX' : $folderParts[1];

            return $client->append($mailbox, $message);

        } elseif (IMAP2_RETROFIT_MODE && is_resource($imap) && get_resource_type($imap) == 'imap') {
            return imap_append($imap, $folder, $message, $options, $internalDate);
        }

        trigger_error(Errors::invalidImapConnection(debug_backtrace(), 1), E_USER_WARNING);

        return false;
    }

    public static function getSubscribed($imap, $mailbox)
    {
        if (is_a($imap, Connection::class)) {
            $client = $imap->getClient();

            return $client->deleteFolder($mailbox);
        }

        return imap_deletemailbox($imap, $mailbox);
    }

    public static function listSubscribed($imap, $mailbox)
    {
        if (is_a($imap, Connection::class)) {
            $client = $imap->getClient();

            return $client->deleteFolder($mailbox);
        }

        return imap_deletemailbox($imap, $mailbox);
    }

    public static function subscribe($imap, $mailbox)
    {
        if (is_a($imap, Connection::class)) {
            $client = $imap->getClient();

            return $client->deleteFolder($mailbox);
        }

        return imap_deletemailbox($imap, $mailbox);
    }

    public static function unsubscribe($imap, $mailbox)
    {
        if (is_a($imap, Connection::class)) {
            $client = $imap->getClient();

            return $client->deleteFolder($mailbox);
        }

        return imap_deletemailbox($imap, $mailbox);
    }
}
