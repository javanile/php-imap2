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

class HeaderInfo
{

    public static function fromMessage($message, $defaultHost)
    {
        file_put_contents('t3.json', json_encode($message, JSON_PRETTY_PRINT));

        $replyTo = $message->replyto ?: $message->from;

        return (object) [
            'date' => $message->date,
            'Date' => $message->date,
            'subject' => $message->subject,
            'Subject' => $message->subject,
            'message_id' => $message->envelope[9],
            'toaddress' => $message->to,
            'to' => self::parseAddressList($message->to, $defaultHost),
            'fromaddress' => $message->from,
            'from' => self::parseAddressList($message->from, $defaultHost),
            'reply_toaddress' => $replyTo ,
            'reply_to' => self::parseAddressList($replyTo, $defaultHost),
            'senderaddress' => $message->from,
            'sender' => self::parseAddressList($message->from, $defaultHost),
            'Recent' => ' ',
            'Unseen' => ' ',
            'Flagged' => ' ',
            'Answered' => ' ',
            'Deleted' => ' ',
            'Draft' => ' ',
            'Msgno' => str_pad($message->id, 4, ' ', STR_PAD_LEFT),
            'MailDate' => $message->internaldate,
            'Size' => strval($message->size),
            'udate' => strtotime($message->internaldate)
        ];
    }

    protected static function parseAddressList($address, $defaultHost)
    {
        $addressList = imap_rfc822_parse_adrlist($address, $defaultHost);
        $customAddressList = [];

        foreach ($addressList as $objectEntry) {
            $addressEntry = (object) [
                'personal' => $objectEntry->personal,
                'mailbox' => $objectEntry->mailbox,
                'host' => $objectEntry->host,
            ];

            if (empty($addressEntry->personal)) {
                unset($addressEntry->personal);
            }

            $customAddressList[] = $addressEntry;
        }

        return $customAddressList;
    }
}

