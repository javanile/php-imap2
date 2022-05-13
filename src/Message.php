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

class Message
{
    public static function fetchBody($imap, $messageNum, $section, $flags = 0)
    {
        if (is_a($imap, Connection::class)) {
            $client = $imap->getClient();
            $client->setDebug(true);

            $messages = $client->fetch($imap->getMailboxName(), $messageNum, false, ['BODY['.$section.']']);

            if ($section) {
                return $messages[$messageNum]->bodypart[$section];
            }

            return $messages[$messageNum]->body;
        }

        return imap_fetchbody($imap, $messageNum, $section, $flags);
    }
}
