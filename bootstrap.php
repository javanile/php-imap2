<?php

/*
 * This file is part of the PHP Input package.
 *
 * (c) Francesco Bianco <bianco@javanile.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Javanile\PhpInput\PhpInput;

if (!function_exists('input')) {
    function input($inputName, $defaultValue = null)
    {
        return PhpInput::input($inputName, $defaultValue);
    }
}

if (!function_exists('getallheaders')) {
    function getallheaders()
    {
        return PhpInput::getAllHeaders();
    }
}
