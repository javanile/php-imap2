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

    public static function fromMessage($message)
    {
        file_put_contents('t3.json', json_encode($message, JSON_PRETTY_PRINT));

        return [
            'date' => $message->date,
            'Date' => $message->date,
            'subject' => $message->subject,
            'Subject' => $message->subject,
            'message_id' => $message->envelope[9],
            'toaddress' => 'javanile.develop@gmail.com',
            'to' => [
                [
                    'mailbox' => 'javanile.develop',
                    'host' => 'gmail.com'
                ]
            ],
            'fromaddress' => 'Google <no-reply@accounts.google.com>',
            'from' => [
                [
                    'personal' => 'Google',
                    'mailbox' => 'no-reply',
                    'host' => 'accounts.google.com'
                ]
            ],
            'reply_toaddress' => 'Google <no-reply@accounts.google.com>',
            'reply_to' => [
                [
                    'personal' => 'Google',
                    'mailbox' => 'no-reply',
                    'host' => 'accounts.google.com'
                ]
            ],
            'senderaddress' => 'Google <no-reply@accounts.google.com>',
            'sender' => [
                [
                    'personal' => 'Google',
                    'mailbox' => 'no-reply',
                    'host' => 'accounts.google.com'
                ]
            ],
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
}

