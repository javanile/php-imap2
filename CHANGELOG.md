# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

## [v0.1.11](https://github.com/javanile/php-imap2/compare/0.1.10...0.1.11)

- **INFO** - Release date 2022-11-05
- **CHANGED** - Added support for search on `imap2_sort` ([#25](https://github.com/javanile/php-imap2/issues/25))
- **FIXED** - Undefined variables on `imap2_bodystruct` ([#5](https://github.com/javanile/php-imap2/issues/5))
- **FIXED** - Missing body peek on `imap2_fetch_overview` ([#24](https://github.com/javanile/php-imap2/issues/24))
- **FIXED** - Missing body peek on `imap2_fetchheader` ([#24](https://github.com/javanile/php-imap2/issues/24))

## [v0.1.10](https://github.com/javanile/php-imap2/compare/0.1.9...0.1.10)

- **INFO** - Release date 2022-10-25
- **CHANGED** - Updated `imap2_fetchstructure` message with support over 2000 emails ([#10](https://github.com/javanile/php-imap2/issues/10))
- **FIXED** - Fixed `imap2_headers` missing un-flagged emails ([#17](https://github.com/javanile/php-imap2/issues/17))

## [v0.1.9](https://github.com/javanile/php-imap2/compare/0.1.8...0.1.9) 

- **INFO** - Release date 2022-09-15
- **CHANGED** - Updated `imap2_last_error` message ([#3](https://github.com/javanile/php-imap2/issues/3))
- **FIXED** - Fixed `imap2_fetchmime` unexpected behaviour ([#10](https://github.com/javanile/php-imap2/issues/10))

## [v0.1.8](https://github.com/javanile/php-imap2/compare/0.1.7...0.1.8)

- **INFO** - Release date 2022-09-05
- **CHANGED** - New reopen error message ([#3](https://github.com/javanile/php-imap2/issues/3))
- **FIXED** - Fixed reopen functions ([#3](https://github.com/javanile/php-imap2/issues/3))

## [v0.1.7](https://github.com/javanile/php-imap2/compare/0.1.0...0.1.7)

- **INFO** - Release date 2022-01-01
- **CHANGED** - Documentation amends ([#3](https://github.com/javanile/php-imap2/issues/3))
- **FIXED** - Generic `imap2_open` bugs ([#3](https://github.com/javanile/php-imap2/issues/3)) 
