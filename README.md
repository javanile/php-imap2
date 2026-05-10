<div align="center">

<a href="https://ko-fi.com/francescobianco/goal?g=10">
    <img src="https://raw.githubusercontent.com/javanile/php-imap2/refs/heads/main/docs/banner.svg" alt="Support PHP IMAP2" />
</a>

<br>

<img src="docs/logo.png" width="180" alt="PHP IMAP2 Logo" />

# PHP IMAP2

Modern IMAP extension wrapper for PHP with OAuth2 support.

[![Linter](https://github.com/javanile/php-imap2/actions/workflows/linter.yml/badge.svg)](https://github.com/javanile/php-imap2/actions/workflows/linter.yml)
[![License](https://img.shields.io/github/license/javanile/php-imap2)](LICENSE)
[![PHP Version](https://img.shields.io/badge/php-%3E%3D7.0-777BB4.svg)](https://www.php.net/)
[![Packagist](https://img.shields.io/packagist/v/javanile/php-imap2)](https://packagist.org/packages/javanile/php-imap2)

</div>

---

## Overview

`php-imap2` provides a lightweight and modern interface for working with IMAP mailboxes in PHP, including OAuth2 authentication support for providers like Gmail and Outlook.

Designed as a simple drop-in alternative for projects that need secure IMAP access without legacy authentication.

---

## Features

- OAuth2 authentication support
- Gmail IMAP integration
- Outlook / Microsoft 365 support
- Lightweight API
- Compatible with native PHP IMAP workflows
- Easy integration into existing projects

---

## Requirements

- PHP >= 7.0
- PHP IMAP extension enabled

---

## Installation

Install via Composer:

```bash
composer require javanile/php-imap2
```

Or download the latest release from GitHub.

---

## Quick Start

```php
<?php

$mbh = imap2_open(
    $server,
    $username,
    $token,
    OP_XOAUTH2
);

if (! $mbh) {
    error_log(imap2_last_error());

    throw new RuntimeException(
        'Unable to open the INBOX'
    );
}
```

---

## Gmail OAuth2

Required OAuth scope:

```text
https://mail.google.com/
```

Example IMAP server:

```text
imap.gmail.com:993/imap/ssl
```

---

## Microsoft Outlook / Office365

Example IMAP server:

```text
outlook.office365.com:993/imap/ssl
```

OAuth2 authentication is supported using Microsoft identity platform access tokens.

---

## Sandbox & Examples

### Gmail Demo

- https://replit.com/@frabik/PHP-IMAP2-Google-Demo?v=1#main.php

### Outlook Demo

Coming soon.

---

## Error Handling

```php
$error = imap2_last_error();

if ($error) {
    echo $error;
}
```

---

## Contributing

Contributions, issues, and feature requests are welcome.

Feel free to open a pull request or start a discussion.

---

## Contributors

- [dicode-nl](https://github.com/dicode-nl)
- [glensc](https://github.com/glensc)
- [bago](https://github.com/bago)

---

## Related Projects

- https://php.libhunt.com/php-imap2-alternatives

---

## References

### Microsoft Outlook

- http://wiki.canfigure.net/en/guides/exchange-oauth2

### IMAP & OAuth2

- https://www.atmail.com/blog/imap-commands/
- https://developers.google.com/gmail/imap/xoauth2-protocol
- https://github.com/ddeboer/imap/issues/443#issuecomment-1172158902

---

## Support the Project

If this project helps you, consider supporting development:

👉 https://ko-fi.com/francescobianco
