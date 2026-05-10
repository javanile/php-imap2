<div align="center">


<a href="https://ko-fi.com/francescobianco/goal?g=10">
<img src="https://raw.githubusercontent.com/javanile/php-imap2/refs/heads/main/docs/banner.svg" />
</a>


</div>

---

# php-imap2 - Source Code

This folder contains the core source code of the **php-imap2** library — a modern and modular PHP wrapper for IMAP functions.

The goal of this library is to provide a clean, object-oriented interface for working with IMAP mailboxes, compatible with PHP native functions, while extending functionality with additional helpers and abstractions.

## Structure Overview

Each file in this directory implements a key feature of the library:

- **Acl.php** - IMAP Access Control List (ACL) support.
- **BodyStructure.php** - Parses message body structure from `imap_fetchstructure()`.
- **Connection.php** - Handles connection to the IMAP server and low-level operations.
- **Errors.php** - Centralized error handling for IMAP operations.
- **Functions.php** - Polyfilled or redefined IMAP functions to enhance compatibility.
- **HeaderInfo.php** - Parses headers from IMAP messages.
- **ImapHelpers.php** - Collection of utility methods used across the library.
- **Mail.php** - High-level object representing an email message.
- **Mailbox.php** - Abstraction for IMAP folders and mailbox information.
- **Message.php** - Core representation of a single message, including metadata.
- **Polyfill.php** - Ensures compatibility with environments missing certain IMAP features.
- **Thread.php** - Parses and represents threaded conversations using `imap_thread()`.
- **Timeout.php** - Timeout management for IMAP connections.

## Usage

This folder is not intended to be used directly. Instead, include the library via Composer and use the appropriate namespace in your code.

Example:

```php
use PhpImap2\Connection;
use PhpImap2\Mail;

// Connect to mailbox
$connection = new Connection([
    'host' => 'imap.example.com',
    'user' => 'user@example.com',
    'password' => 'yourpassword',
]);

$mailbox = $connection->getMailbox('INBOX');
$messages = $mailbox->getMessages();

foreach ($messages as $message) {
    echo $message->getSubject();
}
```

## Notes

- Inspired by [Roundcube](https://github.com/roundcube/roundcubemail) internals and IMAP structure.
- Fully tested against various IMAP servers.
- The source is modular and designed to allow testing and replacement of components.

## License

This library is open source and available under the [MIT License](../LICENSE).
