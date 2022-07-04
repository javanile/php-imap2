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

class BodyStructure
{
    protected static $encodingNumber = [
        '8BIT' => 1,
        'BASE64' => 3,
        'QUOTED-PRINTABLE' => 4,
    ];

    public static function fromMessage($message)
    {
        return self::fromBodyStructure($message->bodystructure);
    }

    protected static function fromBodyStructure($structure)
    {
        $parts = [];
        $parameters = [];

        #file_put_contents('t3.json', json_encode($message, JSON_PRETTY_PRINT));

        if (isset($structure[0]) && $structure[0] == 'TEXT') {
            $parameters = self::extractParameters($structure[2], []);

            return (object) [
                'type' => 0,
                'encoding' => self::$encodingNumber[$structure[5]] ?? 0,
                'ifsubtype' => 1,
                'subtype' => $structure[1],
                'ifdescription' => 0,
                'ifid' => 0,
                'lines' => intval($structure[7]),
                'bytes' => intval($structure[6]),
                'ifdisposition' => 0,
                'ifdparameters' => 0,
                'ifparameters' => count($parameters),
                'parameters' => count($parameters) ? $parameters : (object) [],
            ];
        }

        $section = 'parts';
        $subType = 'ALTERNATIVE';
        foreach ($structure as $item) {
            if ($item == 'ALTERNATIVE') {
                $section = 'parameters';
                continue;
            }

            if ($item == 'MIXED') {
                $subType = 'MIXED';
                $section = 'parameters';
                continue;
            }

            if ($section == 'parts') {
                $parts[] = self::extractPart($item);
            } elseif (is_array($item)) {
                $parameters = self::extractParameters($item, $parameters);
            }
        }

        return (object) [
            'type' => 1,
            'encoding' => 0,
            'ifsubtype' => 1,
            'subtype' => $subType,
            'ifdescription' => 0,
            'ifid' => 0,
            'ifdisposition' => 0,
            'ifdparameters' => 0,
            'ifparameters' => 1,
            'parameters' => $parameters,
            'parts' => $parts,
        ];
    }

    protected static function extractPart($item)
    {
        global $countParts;
        
        $attribute = null;
        $parameters = [];

        if (!is_array($item[2])) {
            return $parameters;
        }

        foreach ($item[2] as $value) {
            if (empty($attribute)) {
                $attribute = [
                    'attribute' => $value,
                    'value' => null,
                ];
            } else {
                $attribute['value'] = $value;
                $parameters[] = (object) $attribute;
                $attribute = null;
            }
        }

        $type = 0;
        $linesIndex = 7;
        $bytesIndex = 6;
        if ($item[0] == 'MESSAGE') {
            $type = 2;
            $linesIndex = 9;
        }

        $part = (object) [
            'type' => $type,
            'encoding' => is_numeric($item[5]) ? (self::$encodingNumber[$item[5]] ?? 0) : 0,
            'ifsubtype' => 1,
            'subtype' => $item[1],
            'ifdescription' => 0,
            'ifid' => 0,
            'lines' => intval($item[$linesIndex]),
            'bytes' => intval($item[$bytesIndex]),
            'ifdisposition' => 0,
            'disposition' => null,
            'ifdparameters' => 0,
            'dparameters' => null,
            'ifparameters' => 1,
            'parameters' => $parameters,
        ];

        $dispositionIndex = 9;
        if ($type == 2) {
            $dispositionIndex = 11;
        }
        if (isset($item[$dispositionIndex][0])) {
            $attribute = null;
            $dispositionParameters = [];
            $part->disposition = $item[$dispositionIndex][0];
            if (isset($item[$dispositionIndex][1]) && is_array($item[$dispositionIndex][1])) {
                foreach ($item[$dispositionIndex][1] as $value) {
                    if (empty($attribute)) {
                        $attribute = [
                            'attribute' => $value,
                            'value' => null,
                        ];
                    } else {
                        $attribute['value'] = $value;
                        $dispositionParameters[] = (object)$attribute;
                        $attribute = null;
                    }
                }
            }
            $part->dparameters = $dispositionParameters;
            $part->ifdparameters = 1;
            $part->ifdisposition = 1;
        } else {
            unset($part->disposition);
            unset($part->dparameters);
        }

        return self::processSubParts($item, $part);
    }

    protected static function processSubParts($item, $part)
    {
        if ($item[0] != 'MESSAGE') {
            return $part;
        }

        $part->parts = [
            self::processSubPartAsMessage($item)
        ];

        return $part;
    }
    
    protected static function processSubPartAsMessage($item)
    {
        $message = (object) [
            'type' => 1,
            'encoding' => 0,
            'ifsubtype' => 1,
            'subtype' => 'MIXED',
            'ifdescription' => 0,
            'ifid' => 0,
            'ifdisposition' => 0,
            'ifdparameters' => 0,
            'ifparameters' => 1,
            'parameters' => [
                (object) [
                    'attribute' => 'BOUNDARY',
                    'value' => '=_995890bdbf8bd158f2cbae0e8d966000'
                ]
            ],
            'parts' => [

            ]
        ];

        foreach ($item[8] as $itemPart) {
            $part = (object) [
                'type' => 0,
                'encoding' => 0,
                'ifsubtype' => 1,
                'subtype' => 'PLAIN',
                'ifdescription' => 0,
                'ifid' => 0,
                'lines' => 1,
                'bytes' => 9,
                'ifdisposition' => 0,
                'ifdparameters' => 0,
                'ifparameters' => 1,
                'parameters' => []
            ];

            if (isset($itemPart[][0])) {
                $attribute = null;
                $dispositionParameters = [];
                $part->disposition = $item[$dispositionIndex][0];
                if (isset($item[$dispositionIndex][1]) && is_array($item[$dispositionIndex][1])) {
                    foreach ($item[$dispositionIndex][1] as $value) {
                        if (empty($attribute)) {
                            $attribute = [
                                'attribute' => $value,
                                'value' => null,
                            ];
                        } else {
                            $attribute['value'] = $value;
                            $dispositionParameters[] = (object)$attribute;
                            $attribute = null;
                        }
                    }
                }
                $part->dparameters = $dispositionParameters;
                $part->ifdparameters = 1;
                $part->ifdisposition = 1;
            } else {
                unset($part->disposition);
                unset($part->dparameters);
            }

            $message->parts[] = $part;
        }
        
        return $message;
    }

    protected static function extractParameters($attributes, $parameters)
    {
        if (empty($attributes)) {
            return [];
        }

        $attribute = null;

        foreach ($attributes as $value) {
            if (empty($attribute)) {
                $attribute = [
                    'attribute' => $value,
                    'value' => null,
                ];
            } else {
                $attribute['value'] = $value;
                $parameters[] = (object) $attribute;
                $attribute = null;
            }
        }

        return $parameters;
    }
}
