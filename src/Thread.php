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

class Thread
{
    public static function thread($imap, $flags = SE_FREE)
    {
        if (is_a($imap, Connection::class)) {
            $client = $imap->getClient();
            $client->setDebug(true);

            $result = $client->search($imap->getMailboxName(), $criteria, $flags & SE_UID);

            if (empty($result->count())) {
                return false;
            }

            $messages = $result->get();
            foreach ($messages as &$message) {
                $message = is_numeric($message) ? intval($message) : $message;
            }

            return $messages;
        }

        return imap_search($imap, $criteria, $flags, $charset);
    }
}
