---
layout: default
title: PHP IMAP2
nav_order: 1
---

# PHP IMAP2

Welcome, please, get you a chance to PHP-IMAP2 <https://github.com/javanile/php-imap2>  
This is a FULL implementation of standard PHP IMAP functions like (imap_open, imap_getmailboxes, imap_*, etc...)

This was full tested, every input to imap2_* functions get back the same output of imap_* equivalent  

- <https://github.com/javanile/php-imap2/blob/main/tests/CompatibilityTest.php>


> The IMAP2 works well with OAUTH

- <https://github.com/javanile/php-imap2/blob/main/tests/XoauthTest.php>

This libray can be installed with composer

```shell
composer require javanile/php-imap2
```

This library introduce a easy way to replace the old PHP-IMAP with new one:

> JUST replace all imap_(...) functions with imap2_(...)

** NO OTHER AMENDS are required. **

> Please give me the opportunity to make my sacrifices useful to the community.
