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

        $to = Functions::writeAddressFromEnvelope($message->envelope[5]);
        $from = Functions::writeAddressFromEnvelope($message->envelope[2]);
        $sender = Functions::writeAddressFromEnvelope($message->envelope[3]);

        if (empty($message->replyto)) {
            $replyTo = $from;
        } else {
            $replyTo = Functions::writeAddressFromEnvelope($message->envelope[4]);
        }

        return (object) [
            'date' => $message->date,
            'Date' => $message->date,
            'subject' => $message->envelope[1],
            'Subject' => $message->envelope[1],
            'message_id' => $message->envelope[9],
            'toaddress' => $to,
            'to' => self::parseAddressList($to, $defaultHost),
            'fromaddress' => $from,
            'from' => self::parseAddressList($from, $defaultHost),
            'reply_toaddress' => $replyTo,
            'reply_to' => self::parseAddressList($replyTo, $defaultHost),
            'senderaddress' => $sender,
            'sender' => self::parseAddressList($sender, $defaultHost),
            'Recent' => ' ',
            'Unseen' => ' ',
            'Flagged' => ' ',
            'Answered' => ' ',
            'Deleted' => ' ',
            'Draft' => ' ',
            'Msgno' => str_pad($message->id, 4, ' ', STR_PAD_LEFT),
            'MailDate' => self::sanitizeMailDate($message->internaldate),
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

    public static function sanitizeMailDate($mailDate)
    {
        if ($mailDate[0] == '0') {
            $mailDate = ' '.substr($mailDate, 1);
        }

        return $mailDate;
    }
}

