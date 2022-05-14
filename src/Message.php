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
    public static function search($imap, $criteria, $flags = SE_FREE, $charset = "")
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

    public static function sort($imap, $criteria, $reverse, $flags = 0, $searchCriteria = null, $charset = null)
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

        return imap_sort($imap, $criteria, $reverse, $flags, $searchCriteria, $charset);
    }

    public static function msgno($imap, $messageUid)
    {
        if (is_a($imap, Connection::class)) {
            $client = $imap->getClient();
            $client->setDebug(true);

            return 1;
        }

        return imap_msgno($imap, $messageUid);
    }

    public static function headerInfo($imap, $messageNum, $fromLength = 0, $subjectLength = 0, $defaultHost = null)
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

        return imap_headerinfo($imap, $messageNum, $fromLength = 0, $subjectLength = 0);
    }

    public static function headers($imap, $messageNum, $fromLength = 0, $subjectLength = 0, $defaultHost = null)
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

        return imap_headerinfo($imap, $messageNum, $fromLength = 0, $subjectLength = 0);
    }

    public static function body($imap, $messageNum, $section, $flags = 0)
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

    public static function saveBody($imap, $file, $messageNum, $section = "", $flags = 0)
    {
        if (is_a($imap, Connection::class)) {
            $client = $imap->getClient();
            $client->setDebug(true);

            $messages = $client->fetch($imap->getMailboxName(), $messageNum, false, ['BODY['.$section.']']);

            $body = $section ? $messages[$messageNum]->bodypart[$section] : $messages[$messageNum]->body;

            return file_put_contents($file, $body);
        }

        return imap_savebody($imap, $file, $messageNum, $section, $flags);
    }

    public static function fetchStructure($imap, $messageNum, $flags = 0)
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

        return imap_fetchstructure($imap, $messageNum, $flags);
    }

    public static function bodyStruct($imap, $messageNum, $flags = 0)
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

        return imap_fetchstructure($imap, $messageNum, $flags);
    }

    public static function fetchHeader($imap, $messageNum, $flags = 0)
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

        return imap_fetchheader($imap, $messageNum, $flags);
    }

    public static function fetchOverview($imap, $sequence, $flags = 0)
    {
        if (is_a($imap, Connection::class)) {
            $client = $imap->getClient();
            $client->setDebug(true);

            $messages = $client->fetch($imap->getMailboxName(), $sequence, false, [
                'BODY[HEADER.FIELDS (SUBJECT FROM TO CC REPLYTO MESSAGEID DATE SIZE)]',
                'UID',
                'FLAGS',
                'INTERNALDATE',
                'RFC822.SIZE',
                'ENVELOPE',
                'RFC822.HEADER'
            ]);

            if ($sequence != '*' && count($messages) < Functions::expectedNumberOfMessages($sequence)) {
                return [];
            }

            $overview = [];
            foreach ($messages as $message) {
                #var_dump($message);
                #die();
                $messageEntry = (object) [
                    'subject' => $message->get('subject'),
                    'from' => $message->get('from'),
                    'to' => $message->get('to'),
                    'date' => $message->envelope[0],
                    'message_id' => $message->envelope[9],
                    'size' => $message->size,
                    'uid' => $message->uid,
                    'msgno' => $message->id,
                    'recent' => intval($message->flags['RECENT'] ?? 0),
                    'flagged' => intval($message->flags['FLAGGED'] ?? 0),
                    'answered' => intval($message->flags['ANSWERED'] ?? 0),
                    'deleted' => intval($message->flags['DELETED'] ?? 0),
                    'seen' => intval($message->flags['SEEN'] ?? 0),
                    'draft' => intval($message->flags['DRAFT'] ?? 0),
                    'udate' => $message->timestamp,
                ];
                if (empty($messageEntry->to)) {
                    unset($messageEntry->to);
                }
                $overview[] = $messageEntry;
            }

            return $overview;

        } elseif (IMAP2_RETROFIT_MODE && is_resource($imap) && get_resource_type($imap) == 'imap') {
            return imap_fetch_overview($imap, $sequence, $flags);
        }

        trigger_error(Errors::invalidImapConnection(debug_backtrace(), 1), E_USER_WARNING);

        return false;
    }

    public static function delete($imap, $messageNums, $flags = 0)
    {
        if (is_a($imap, Connection::class)) {
            $client = $imap->getClient();

            $messages = $client->fetch($imap->getMailboxName(), $messageNums, false, ['UID']);
            foreach ($messages as $message) {
                $client->flag($imap->getMailboxName(), $message->uid, $client->flags['DELETED']);
            }

            return true;
        }

        return imap_undelete($imap, $messageNums, $flags);
    }

    public static function undelete($imap, $messageNums, $flags = 0)
    {
        if (is_a($imap, Connection::class)) {
            $client = $imap->getClient();
            $client->setDebug(true);

            $messages = $client->fetch($imap->getMailboxName(), $messageNums, false, ['UID']);
            foreach ($messages as $message) {
                $client->unflag($imap->getMailboxName(), $message->uid, $client->flags['DELETED']);
            }

            return true;
        }

        return imap_undelete($imap, $messageNums, $flags);
    }

    public static function expunge($imap)
    {
        if (is_a($imap, Connection::class)) {
            $client = $imap->getClient();

            return $client->expunge($imap->getMailboxName());
        }

        return imap_expunge($imap);
    }

    public static function clearFlagFull($imap)
    {
        if (is_a($imap, Connection::class)) {
            $client = $imap->getClient();

            return $client->expunge($imap->getMailboxName());
        }

        return imap_expunge($imap);
    }

    public static function uid($imap)
    {
        if (is_a($imap, Connection::class)) {
            $client = $imap->getClient();

            return $client->expunge($imap->getMailboxName());
        }

        return imap_expunge($imap);
    }
}
