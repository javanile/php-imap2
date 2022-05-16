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



        return [
            'date' => $message->date,
            'Date' => $message->date,
            'subject' => $message->subject,
            'Subject' => $message->subject,
            'message_id' => $message->envelope[9],
            'toaddress' => $message->to,
            'to' => imap_rfc822_parse_adrlist($message->to, $defaultHost),
            'fromaddress' => $message->from,
            'from' => imap_rfc822_parse_adrlist($message->from, $defaultHost),
            'reply_toaddress' => $message->replyto ,
            'reply_to' => $message->replyto ? imap_rfc822_parse_adrlist($message->replyto, $defaultHost) : null,
            'senderaddress' => $message->from,
            'sender' => imap_rfc822_parse_adrlist($message->from, $defaultHost),
            'Recent' => ' ',
            'Unseen' => ' ',
            'Flagged' => ' ',
            'Answered' => ' ',
            'Deleted' => ' ',
            'Draft' => ' ',
            'Msgno' => '   1',
            'MailDate' => '26-Feb-2021 16:53:30 +0000',
            'Size' => '11659',
            'udate' => 1614358410
        ];
    }

    protected static function parseAddressList($address, $defaultHost)
    {
        $addressList = imap_rfc822_parse_adrlist($address, $defaultHost);
        $customAddressList = [];

        foreach ($addressList as $entry) {
            $customAddressList[] = (object) [
                'personal' => $entry->personal,
                'mailbox' => $entry->mailbox,
                'host' => $entry->host,
            ];
        }

        return $customAddressList;
    }
}

