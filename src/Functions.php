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

class Functions
{
    public static function parseMailboxString($mailbox)
    {
        $mailboxParts = explode('}', $mailbox);
        $mailboxParts[0] = substr($mailboxParts[0], 1);

        $values = parse_url($mailboxParts[0]);

        $values['mailbox'] = $mailboxParts[1] ?? '';
        $values['path'] = explode('/', $values['path']);

        return $values;
    }

    public static function getHostFromMailbox($mailbox)
    {
        $mailboxParts = is_array($mailbox) ? $mailbox : self::parseMailboxString($mailbox);

        return $mailboxParts['host'];
    }

    public static function getSslModeFromMailbox($mailbox)
    {
        $mailboxParts = is_array($mailbox) ? $mailbox : self::parseMailboxString($mailbox);

        if (in_array('ssl', $mailboxParts['path'])) {
            return 'ssl';
        }

        return false;
    }

    public static function expectedNumberOfMessages($sequence)
    {
        if (strpos($sequence, ',') > 0) {
            return count(explode(',', $sequence));
        } elseif (strpos($sequence, ':') > 0) {
            $range = explode(':', $sequence);
            return $range[1] - $range[0];
        } else {
            return 1;
        }
    }

    public static function unique()
    {
        return md5(microtime(). time() . rand(1000, 9999));
    }

    /**
     * Get name from full mailbox string.
     *
     * @param $mailbox
     *
     * @return mixed|string
     */
    public static function getMailboxName($mailbox)
    {
        $mailboxParts = explode('}', $mailbox, 2);

        return $mailboxParts[1] ?? 'INBOX';
    }
}
