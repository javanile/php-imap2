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

class Connection
{
    /**
     * Extract input value using input name from the context otherwise get back a default value.
     *
     * @param $inputName
     * @param $defaultValue
     * @return void
     */
    public static function open($mailbox, $user, $password, $flags = 0, $retries = 0, $options = [])
    {
        if ($flags & OP_XOAUTH2 || !function_exists('imap_open')) {
            $connection = new Connection($mailbox, $user, $password, $flags, $retries, $options);

            return $connection->connect();
        }

        return imap_open($mailbox, $user, $password, $flags, $retries, $options);
    }
}
