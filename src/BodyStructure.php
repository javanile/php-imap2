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
        'BASE64' => 3,
        'QUOTED-PRINTABLE' => 4,
    ];

    public static function fromMessage($message)
    {
        $parts = [];
        $parameters = [];

        file_put_contents('t3.json', json_encode($message, JSON_PRETTY_PRINT));

        if (isset($message->bodystructure[0]) && $message->bodystructure[0] == 'TEXT') {
            return (object) [
                'type' => 0,
                'encoding' => 0,
                'ifsubtype' => 1,
                'subtype' => $message->bodystructure[1],
                'ifdescription' => 0,
                'ifid' => 0,
                'lines' => intval($message->bodystructure[7]),
                'bytes' => intval($message->bodystructure[6]),
                'ifdisposition' => 0,
                'ifdparameters' => 0,
                'ifparameters' => 0,
                'parameters' => (object) [],
            ];
        }

        $section = 'parts';
        $subType = 'ALTERNATIVE';
        foreach ($message->bodystructure as $item) {
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
                $attribute = null;
                foreach ($item as $value) {
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

        $part = (object) [
            'type' => 0,
            'encoding' => self::$encodingNumber[$item[5]],
            'ifsubtype' => 1,
            'subtype' => $item[1],
            'ifdescription' => 0,
            'ifid' => 0,
            'lines' => intval($item[7]),
            'bytes' => intval($item[6]),
            'ifdisposition' => 0,
            'disposition' => null,
            'ifdparameters' => 0,
            'dparameters' => null,
            'ifparameters' => 1,
            'parameters' => $parameters,
        ];

        if (isset($item[9][0])) {
            $attribute = null;
            $dispositionParameters = [];
            $part->disposition = $item[9][0];
            foreach ($item[9][1] as $value) {
                if (empty($attribute)) {
                    $attribute = [
                        'attribute' => $value,
                        'value' => null,
                    ];
                } else {
                    $attribute['value'] = $value;
                    $dispositionParameters[] = (object) $attribute;
                    $attribute = null;
                }
            }
            $part->dparameters = $dispositionParameters;
            $part->ifdparameters = count($dispositionParameters);
            $part->ifdisposition = 1;
        } else {
            unset($part->disposition);
            unset($part->dparameters);
        }

        return $part;
    }
}
