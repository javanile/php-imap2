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

if (!function_exists('imap2_createmailbox')) {
    function imap2_createmailbox($imap, $mailbox)
    {
        return Mailbox::createMailbox($imap, $mailbox);
    }
}

if (!function_exists('imap2_deletemailbox')) {
    function imap2_deletemailbox($imap, $mailbox)
    {
        return Mailbox::deleteMailbox($imap, $mailbox);
    }
}

if (!function_exists('imap2_search')) {
    function imap2_search($imap, $criteria, $flags = SE_FREE, $charset = "")
    {
        return Message::search($imap, $criteria, $flags, $charset);
    }
}

if (!function_exists('imap2_msgno')) {
    function imap2_msgno($imap, $messageUid)
    {
        return Message::msgno($imap, $messageUid);
    }
}

if (!function_exists('imap2_sort')) {
    function imap2_sort($imap, $criteria, $reverse, $flags = 0, $searchCriteria = null, $charset = null)
    {
        return Message::sort($imap, $criteria, $reverse, $flags, $searchCriteria, $charset);
    }
}

if (!function_exists('imap2_headerinfo')) {
    function imap2_headerinfo($imap, $messageNum, $fromLength = 0, $subjectLength = 0)
    {
        return Message::headerInfo($imap, $messageNum, $fromLength, $subjectLength);
    }
}

if (!function_exists('imap2_fetchbody')) {
    function imap2_fetchbody($imap, $messageNum, $section, $flags = 0)
    {
        return Message::fetchBody($imap, $messageNum, $section, $flags);
    }
}

if (!function_exists('imap2_savebody')) {
    function imap2_savebody($imap, $file, $messageNum, $section = "", $flags = 0)
    {
        return Message::saveBody($imap, $file, $messageNum, $section, $flags);
    }
}

if (!function_exists('imap2_fetchstructure')) {
    function imap2_fetchstructure($imap, $messageNum, $flags = 0)
    {
        return Message::fetchStructure($imap, $messageNum, $flags);
    }
}

if (!function_exists('imap2_fetchheader')) {
    function imap2_fetchheader($imap, $messageNum, $flags = 0)
    {
        return Message::fetchHeader($imap, $messageNum, $flags);
    }
}

if (!function_exists('imap2_delete')) {
    function imap2_delete($imap, $messageNums, $flags = 0)
    {
        return Message::delete($imap, $messageNums, $flags);
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
