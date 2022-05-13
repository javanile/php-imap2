<?php

/*
 * This file is part of the PHP Input package.
 *
 * (c) Francesco Bianco <bianco@javanile.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Javanile\Imap2\Connection;
use Javanile\Imap2\Errors;
use Javanile\Imap2\Mailbox;
use Javanile\Imap2\Message;
use Javanile\Imap2\Thread;
use Javanile\Imap2\Polyfill;

if (!defined('OP_XOAUTH2')) {
    define('OP_XOAUTH2', 512);
}

/**
 * imap2_open
 */
if (!function_exists('imap_open')) {
    function imap_open($mailbox, $user, $password, $flags = 0, $retries = 0, $options = [])
    {
        return imap2_open($mailbox, $user, $password, $flags, $retries, $options);
    }
}
if (!function_exists('imap2_open')) {
    function imap2_open($mailbox, $user, $password, $flags = 0, $retries = 0, $options = [])
    {
        return Connection::open($mailbox, $user, $password, $flags, $retries, $options);
    }
}

if (!function_exists('imap2_reopen')) {
    function imap2_reopen($imap, $mailbox, $flags = 0, $retries = 0)
    {
        return Connection::reopen($imap, $mailbox, $flags, $retries);
    }
}

if (!function_exists('imap2_ping')) {
    function imap2_ping($imap)
    {
        return Connection::ping($imap);
    }
}

if (!function_exists('imap2_close')) {
    function imap2_close($imap, $flags = 0)
    {
        return Connection::close($imap, $flags);
    }
}

if (!function_exists('imap2_check')) {
    function imap2_check($imap)
    {
        return Mailbox::check($imap);
    }
}

if (!function_exists('imap2_status')) {
    function imap2_status($imap, $mailbox, $flags)
    {
        return Mailbox::status($imap, $mailbox, $flags);
    }
}

if (!function_exists('imap2_list')) {
    function imap2_list($imap, $reference, $pattern)
    {
        return Mailbox::list($imap, $reference, $pattern);
    }
}

if (!function_exists('imap2_getmailboxes')) {
    function imap2_getmailboxes($imap, $reference, $pattern)
    {
        return Mailbox::getMailboxes($imap, $reference, $pattern);
    }
}

/**
 * imap2_createmailbox
 */
if (!function_exists('imap_createmailbox')) {
    function imap_createmailbox($imap, $mailbox)
    {
        return imap2_createmailbox($imap, $mailbox);
    }
}
if (!function_exists('imap2_createmailbox')) {
    function imap2_createmailbox($imap, $mailbox)
    {
        return Mailbox::createMailbox($imap, $mailbox);
    }
}

/**
 * imap2_deletemailbox
 */
if (!function_exists('imap_deletemailbox')) {
    function imap_deletemailbox($imap, $mailbox)
    {
        return imap2_deletemailbox($imap, $mailbox);
    }
}
if (!function_exists('imap2_deletemailbox')) {
    function imap2_deletemailbox($imap, $mailbox)
    {
        return Mailbox::deleteMailbox($imap, $mailbox);
    }
}

/**
 * imap_search
 */
if (!function_exists('imap_search')) {
    function imap_search($imap, $criteria, $flags = SE_FREE, $charset = "")
    {
        return imap2_search($imap, $criteria, $flags, $charset);
    }
}
if (!function_exists('imap2_search')) {
    function imap2_search($imap, $criteria, $flags = SE_FREE, $charset = "")
    {
        return Message::search($imap, $criteria, $flags, $charset);
    }
}

/**
 * imap2_msgno
 */
if (!function_exists('imap_msgno')) {
    function imap_msgno($imap, $messageUid)
    {
        return imap2_msgno($imap, $messageUid);
    }
}
if (!function_exists('imap2_msgno')) {
    function imap2_msgno($imap, $messageUid)
    {
        return Message::msgno($imap, $messageUid);
    }
}

/**
 * imap2_sort
 */
if (!function_exists('imap_sort')) {
    function imap_sort($imap, $criteria, $reverse, $flags = 0, $searchCriteria = null, $charset = null)
    {
        return imap2_sort($imap, $criteria, $reverse, $flags, $searchCriteria, $charset);
    }
}
if (!function_exists('imap2_sort')) {
    function imap2_sort($imap, $criteria, $reverse, $flags = 0, $searchCriteria = null, $charset = null)
    {
        return Message::sort($imap, $criteria, $reverse, $flags, $searchCriteria, $charset);
    }
}

/**
 *
 */
if (!function_exists('imap_append')) {
    function imap_append($imap, $folder, $message, $options = null, $internalDate = null)
    {
        return imap2_append($imap, $folder, $message, $options, $internalDate);
    }
}
if (!function_exists('imap2_append')) {
    function imap2_append($imap, $folder, $message, $options = null, $internalDate = null)
    {
        return Mailbox::append($imap, $folder, $message, $options, $internalDate);
    }
}

/**
 * imap2_headerinfo
 */
if (!function_exists('imap_headerinfo')) {
    function imap_headerinfo($imap, $messageNum, $fromLength = 0, $subjectLength = 0)
    {
        return imap2_headerinfo($imap, $messageNum, $fromLength, $subjectLength);
    }
}
if (!function_exists('imap2_headerinfo')) {
    function imap2_headerinfo($imap, $messageNum, $fromLength = 0, $subjectLength = 0)
    {
        return Message::headerInfo($imap, $messageNum, $fromLength, $subjectLength);
    }
}

/**
 * imap2_body
 */
if (!function_exists('imap_body')) {
    function imap_body($imap, $messageNum, $flags = 0)
    {
        return imap2_body($imap, $messageNum, $flags);
    }
}
if (!function_exists('imap2_body')) {
    function imap2_body($imap, $messageNum, $flags = 0)
    {
        return Message::body($imap, $messageNum, $flags);
    }
}

/**
 * imap2_fetchbody
 */
if (!function_exists('imap_fetchbody')) {
    function imap_fetchbody($imap, $messageNum, $section, $flags = 0)
    {
        return imap2_fetchbody($imap, $messageNum, $section, $flags);
    }
}
if (!function_exists('imap2_fetchbody')) {
    function imap2_fetchbody($imap, $messageNum, $section, $flags = 0)
    {
        return Message::fetchBody($imap, $messageNum, $section, $flags);
    }
}

/**
 * imap2_bodystruct
 */
if (!function_exists('imap_bodystruct')) {
    function imap_bodystruct($imap, $messageNum, $section)
    {
        return imap2_bodystruct($imap, $messageNum, $section);
    }
}
if (!function_exists('imap2_bodystruct')) {
    function imap2_bodystruct($imap, $messageNum, $section)
    {
        return Message::bodyStruct($imap, $messageNum, $section);
    }
}

/**
 * imap2_savebody
 */
if (!function_exists('imap_savebody')) {
    function imap_savebody($imap, $file, $messageNum, $section = "", $flags = 0)
    {
        return imap2_savebody($imap, $file, $messageNum, $section, $flags);
    }
}
if (!function_exists('imap2_savebody')) {
    function imap2_savebody($imap, $file, $messageNum, $section = "", $flags = 0)
    {
        return Message::saveBody($imap, $file, $messageNum, $section, $flags);
    }
}

/**
 * imap2_fetchstructure
 */
if (!function_exists('imap_fetchstructure')) {
    function imap_fetchstructure($imap, $messageNum, $flags = 0)
    {
        return imap2_fetchstructure($imap, $messageNum, $flags);
    }
}
if (!function_exists('imap2_fetchstructure')) {
    function imap2_fetchstructure($imap, $messageNum, $flags = 0)
    {
        return Message::fetchStructure($imap, $messageNum, $flags);
    }
}

/**
 * imap2_fetchheader
 */
if (!function_exists('imap_fetchheader')) {
    function imap_fetchheader($imap, $messageNum, $flags = 0)
    {
        return imap2_fetchheader($imap, $messageNum, $flags);
    }
}
if (!function_exists('imap2_fetchheader')) {
    function imap2_fetchheader($imap, $messageNum, $flags = 0)
    {
        return Message::fetchHeader($imap, $messageNum, $flags);
    }
}

/**
 * imap2_delete
 */
if (!function_exists('imap_delete')) {
    function imap_delete($imap, $messageNums, $flags = 0)
    {
        return imap2_delete($imap, $messageNums, $flags);
    }
}
if (!function_exists('imap2_delete')) {
    function imap2_delete($imap, $messageNums, $flags = 0)
    {
        return Message::delete($imap, $messageNums, $flags);
    }
}

/**
 * imap2_undelete
 */
if (!function_exists('imap_undelete')) {
    function imap_undelete($imap, $messageNums, $flags = 0)
    {
        return imap2_undelete($imap, $messageNums, $flags);
    }
}
if (!function_exists('imap2_undelete')) {
    function imap2_undelete($imap, $messageNums, $flags = 0)
    {
        return Message::undelete($imap, $messageNums, $flags);
    }
}

/**
 * imap2_expunge
 */
if (!function_exists('imap_expunge')) {
    function imap_expunge($imap)
    {
        return imap2_expunge($imap);
    }
}
if (!function_exists('imap2_expunge')) {
    function imap2_expunge($imap)
    {
        return Message::expunge($imap);
    }
}

/**
 * imap2_thread
 */
if (!function_exists('imap_thread')) {
    function imap_thread($imap, $flags = SE_FREE)
    {
        return imap2_thread($imap, $flags);
    }
}
if (!function_exists('imap2_thread')) {
    function imap2_thread($imap, $flags = SE_FREE)
    {
        return Thread::thread($imap, $flags);
    }
}

/**
 * imap2_errors
 */
if (!function_exists('imap_errors')) {
    function imap_errors()
    {
        return imap2_errors();
    }
}
if (!function_exists('imap2_errors')) {
    function imap2_errors()
    {
        return Errors::errors();
    }
}

/**
 * imap2_alerts
 */
if (!function_exists('imap_alerts')) {
    function imap_alerts()
    {
        return imap2_alerts();
    }
}
if (!function_exists('imap2_alerts')) {
    function imap2_alerts()
    {
        return Errors::alerts();
    }
}

/**
 * imap2_8bit
 */
if (!function_exists('imap_8bit')) {
    function imap_8bit($string)
    {
        return imap2_8bit($string);
    }
}
if (!function_exists('imap2_8bit')) {
    function imap2_8bit($string)
    {
        return function_exists('imap_8bit') ? imap_8bit($string) : Polyfill::convert8bit($string);
    }
}

/**
 * imap2_base64
 */
if (!function_exists('imap_base64')) {
    function imap_base64($string)
    {
        return imap2_base64($string);
    }
}
if (!function_exists('imap2_base64')) {
    function imap2_base64($string)
    {
        return function_exists('imap_base64') ? imap_base64($string) : Polyfill::base64($string);
    }
}

/**
 * imap2_binary
 */
if (!function_exists('imap_binary')) {
    function imap_base64($string)
    {
        return imap2_binary($string);
    }
}
if (!function_exists('imap2_binary')) {
    function imap2_binary($string)
    {
        return function_exists('imap_binary') ? imap_binary($string) : Polyfill::binary($string);
    }
}


