---
layout: default
title: Compatibility
nav_order: 3
---

## Compatibility

This page is intended to track the compatibility of all standard PHP [IMAP functions](https://www.php.net/manual/en/ref.imap.php) over the ones provided by [PHP-IMAP2](https://php-imap2.javanile.org/functions.html)
For each function is reported a list of issues born around problems on real use cases. Please add new issue if your have some concerns with specific function.
All below checked functions are tested with complex tests and will be considered an exact equivalent of the original one.   
If your function is not checked, no problem, it works but can be effected by a small difference from the original one. Ask for support and create a new issue if your have special needs or mandatory behaviour to fix. 

- [x] [imap_alerts](https://github.com/javanile/php-imap2/issues?q=label%3Aalerts)
- [x] [imap_append](https://github.com/javanile/php-imap2/issues?q=label%3Aappend)
- [x] [imap_check](https://github.com/javanile/php-imap2/issues?q=label%3Acheck)
- [ ] [imap_clearflag_full](https://github.com/javanile/php-imap2/issues?q=label%3Aclearflag_full)
- [x] [imap_close](https://github.com/javanile/php-imap2/issues?q=label%3Aclose)
- [ ] [imap_createmailbox](https://github.com/javanile/php-imap2/issues?q=label%3Acreatemailbox)
- [ ] [imap_delete](https://github.com/javanile/php-imap2/issues?q=label%3Adelete)
- [ ] [imap_deletemailbox](https://github.com/javanile/php-imap2/issues?q=label%3Adeletemailbox)
- [ ] [imap_errors](https://github.com/javanile/php-imap2/issues?q=label%3Aerrors)
- [ ] [imap_expunge](https://github.com/javanile/php-imap2/issues?q=label%3Aexpunge)
- [ ] [imap_fetchbody](https://github.com/javanile/php-imap2/issues?q=label%3Afetchbody)
- [x] [imap_fetchheader](https://github.com/javanile/php-imap2/issues?q=label%3Afetchheader)
- [ ] [imap_fetch_overview](https://github.com/javanile/php-imap2/issues?q=label%3Afetch_overview)
- [x] [imap_fetchstructure](https://github.com/javanile/php-imap2/issues?q=label%3Afetchstructure)
- [ ] [imap_getmailboxes](https://github.com/javanile/php-imap2/issues?q=label%3Agetmailboxes)
- [ ] [imap_get_quotaroot](https://github.com/javanile/php-imap2/issues?q=label%3Aget_quotaroot)
- [x] [imap_headerinfo](https://github.com/javanile/php-imap2/issues?q=label%3Aheaderinfo)
- [x] [imap_last_error](https://github.com/javanile/php-imap2/issues?q=label%3Alast_error)
- [x] [imap_mail_copy](https://github.com/javanile/php-imap2/issues?q=label%3Amail_move)
- [x] [imap_mail_move](https://github.com/javanile/php-imap2/issues?q=label%3Afetchbody)
- [x] [imap_mime_header_decode](https://github.com/javanile/php-imap2/issues?q=label%3Amime_header_decode)
- [x] [imap_msgno](https://github.com/javanile/php-imap2/issues?q=label%3Amsgno)
- [x] [imap_num_msg](https://github.com/javanile/php-imap2/issues?q=label%3Anum_msg)
- [ ] [imap_open](https://github.com/javanile/php-imap2/issues?q=label%3Aopen)
- [x] [imap_ping](https://github.com/javanile/php-imap2/issues?q=label%3Aping)
- [ ] [imap_reopen](https://github.com/javanile/php-imap2/issues?q=label%3Areopen)
- [ ] [imap_savebody](https://github.com/javanile/php-imap2/issues?q=label%3Asavebody)
- [ ] [imap_search](https://github.com/javanile/php-imap2/issues?q=label%3Asearch)
- [ ] [imap_setflag_full](https://github.com/javanile/php-imap2/issues?q=label%3Asetflag_full)
- [ ] [imap_sort](https://github.com/javanile/php-imap2/issues?q=label%3Asort)
- [ ] [imap_status](https://github.com/javanile/php-imap2/issues?q=label%3Astatus)
- [ ] [imap_thread](https://github.com/javanile/php-imap2/issues?q=label%3Athread)
- [ ] [imap_timeout](https://github.com/javanile/php-imap2/issues?q=label%3Atimeout)
- [ ] [imap_undelete](https://github.com/javanile/php-imap2/issues?q=label%3Aundelete)

Your function not is on the list. [Submit an issue for it.](https://github.com/javanile/php-imap2/issues/new)
