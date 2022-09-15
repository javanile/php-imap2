---
layout: default
title: Examples
nav_order: 4
---

# Examples

## Gmail

## Outlook

```php
<?php
$mailbox = '{outlook.office365.com:993/imap/ssl}';
$username = 'sam.sapiol@contoso.onmicrosoft.com';
$accessToken = '...';

$imap = imap2_open($mailbox, $username, $accessToken, OP_XOAUTH2);
```
