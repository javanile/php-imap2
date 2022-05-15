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

class ImapHelpers
{
    /**
     * Convert a string contain a sequence of message id to and equivalent with uid.
     *
     * @param $imap
     * @param $messageNums
     *
     * @return string
     */
    public static function idToUid($imap, $messageNums)
    {
        $client = $imap->getClient();

        $messages = $client->fetch($imap->getMailboxName(), $messageNums, false, ['UID']);

        $uid = [];
        foreach ($messages as $message) {
            $uid[] = $message->uid;
        }

        return implode(',', $uid);
    }
}
