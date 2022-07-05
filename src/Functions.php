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

        return empty($mailboxParts[1]) ? 'INBOX' : $mailboxParts[1];
    }

    /**
     *
     * @param $address
     * @param $defaultHost
     *
     * @return string
     */
    public static function sanitizeAddress($address, $defaultHost = 'UNKNOWN')
    {
        $addressList = imap_rfc822_parse_adrlist($address, $defaultHost);

        $sanitizedAddress = [];
        foreach ($addressList as $addressEntry) {
            $sanitizedAddress[] = imap_rfc822_write_address($addressEntry->mailbox, $addressEntry->host, $addressEntry->personal);
        }

        return implode(', ', $sanitizedAddress);
    }

    /**
     *
     */
    public static function writeAddressFromEnvelope($addressList)
    {
        $sanitizedAddress = [];
        foreach ($addressList as $addressEntry) {
            $sanitizedAddress[] = imap_rfc822_write_address($addressEntry[2], $addressEntry[3], $addressEntry[0]);
        }

        return implode(', ', $sanitizedAddress);
    }

    /**
     *
     */
    public static function getListAttributesValue($attributes)
    {
        $attributesValue = 0;

        foreach ($attributes as $attribute) {
            switch ($attribute) {
                case '\\NoInferiors':
                    $attributesValue |= LATT_NOINFERIORS;
                    break;
                case '\\NoSelect':
                    $attributesValue |= LATT_NOSELECT;
                    break;
                case '\\Marked':
                    $attributesValue |= LATT_MARKED;
                    break;
                case '\\UnMarked':
                    $attributesValue |= LATT_UNMARKED;
                    break;
                case '\\Referral':
                    $attributesValue |= LATT_REFERRAL;
                    break;
                case '\\HasChildren':
                    $attributesValue |= LATT_HASCHILDREN;
                    break;
                case '\\HasNoChildren':
                    $attributesValue |= LATT_HASNOCHILDREN;
                    break;
            }
        }

        return $attributesValue;
    }

    public static function isValidImap1Connection($imap)
    {
        return is_resource($imap) && get_resource_type($imap) == 'imap';
    }

    public static function isValidImap2Connection($imap)
    {
        return Connection::isValid($imap);
    }

    public static function getAddressObjectList($addressList)
    {
        $addressObjectList = [];
        foreach ($addressList as $toAddress) {
            $email = explode('@', $toAddress->getEmail());

            $addressObject = (object) [
                'mailbox' => $email[0],
                'host' => $email[1],
            ];

            $addressObjectList[] = $addressObject;
        }

        return $addressObjectList;
    }
}
