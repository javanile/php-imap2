<?php

/*
 * This file is part of the PHP Input package.
 *
 * (c) Francesco Bianco <bianco@javanile.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Javanile\PhpInput;

class PhpInput
{
    /**
     * Extract input value using input name from the context otherwise get back a default value.
     *
     * @param $inputName
     * @param $defaultValue
     * @return void
     */
    public static function input($inputName, $defaultValue = null)
    {
        $flatInputName = self::flatInputName($inputName);
        $inputStages = [$_POST, $_GET];

        foreach ($inputStages as $inputArray) {
            if (self::inputLookup($inputArray, $flatInputName, $inputValue) === true) {
                return $inputValue;
            }
        }

        if (self::inputLookup(getallheaders(), $flatInputName, $inputValue) === true) {
            return $inputValue;
        }

        if (self::inputLookup(getenv(), $flatInputName, $inputValue) === true) {
            return $inputValue;
        }

        return $defaultValue;
    }

    /**
     * Lookup for input value inside specific input stage.
     *
     * @param $inputStage
     * @param $flatInputName
     * @param $inputValue
     * @return void
     */
    protected static function inputLookup($inputArray, $flatInputName, &$inputValue)
    {
        if (!is_array($inputArray)) {
            return;
        }

        foreach ($inputArray as $inputName => $inputValue) {
            if ($flatInputName == self::flatInputName($inputName)) {
                return true;
            }
        }
    }

    /**
     *
     */
    protected static function flatInputName($inputName)
    {
        return strtolower(strtr(trim($inputName), ' -', '__'));
    }

    /**
     * @return array
     */
    public static function getAllHeaders()
    {
        $headers = [];
        foreach ($_SERVER as $name => $value) {
            if (substr($name, 0, 5) == 'HTTP_') {
                $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
        }
        return $headers;
    }
}
