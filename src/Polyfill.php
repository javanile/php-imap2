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

class Polyfill
{
    public static function convert8bit($string)
    {
        return $string;
    }

    public static function mimeHeaderDecode($string)
    {
        return $string;
    }

    public static function mutf7ToUtf8($string)
    {
        return $string;
    }

    public static function qPrint($string)
    {
        return $string;
    }

    public static function mailCompose($envelope, $bodies)
    {
        return false;
    }
}
