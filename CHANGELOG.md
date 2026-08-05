# Changelog

All notable changes will be documented in this file.

## [4.3.0] - 2026-08-05

### Changed
- Replaced the abandoned `nunomaduro/larastan` dev dependency with the maintained `larastan/larastan` (`^2.0|^3.0`)
- PHPStan include path updated to `vendor/larastan/larastan/extension.neon`
- `symfony/http-client` and `symfony/mailgun-mailer` dev constraints widened to `^6.0|^7.0|^8.0` to test against the Symfony 8 Mailgun bridge used by Laravel 13 on PHP 8.4

## [4.2.0] - 2026-03-26

### Changed
- `pestphp/pest` and `pestphp/pest-plugin-laravel` dev constraints widened to `^4.0` for Laravel 13 compatibility
- README requirements and Laravel badge updated to include Laravel 13

### Fixed
- Code style: import `MailManager` and `Error` instead of using fully qualified names (Pint `fully_qualified_strict_types`)

## [4.1.0] - 2026-03-16

### Added
- Support for Laravel 13: `illuminate/contracts`, `illuminate/mail` and `illuminate/support` now allow `^13.0`
- CI matrix extended with Laravel 13, excluding PHP 8.2 which Laravel 13 does not support

### Changed
- `orchestra/testbench` dev constraint widened to `^11.0`

## [4.0.1] - 2026-02-20

### Fixed
- CI: PHPStan memory limit increased to 512M to avoid worker crashes

### Changed
- Removed unused `MailgunConfigKey` enum and `MailgunTransportException` class
- Removed obsolete `psalm.xml` reference from `.gitattributes`

### Added
- Tests for missing and empty endpoint in config resolver

## [4.0.0] - 2026-02-19

### Changed
- Namespace changed from `SkitLabs\LaravelMailGunMultipleDomains` to `Laratusk\LaravelMailgunMulti`
- Minimum PHP version bumped to 8.2
- Minimum Laravel version bumped to 10.0
- DTO `MailgunSenderProperties` is now a `readonly class`
- Resolver renamed to `ConfigBasedMailgunSenderPropertiesResolver`
- ServiceProvider renamed to `MailgunMultiServiceProvider`
- All classes are now `final`

### Added
- Custom exceptions: `InvalidMailgunConfigException`, `MailgunTransportException`
- Config validation for secret and endpoint
- Optional logging for domain switches (`services.mailgun.log_domain_switches`)
- `MailgunConfigKey` enum
- PHPStan level 8 static analysis
- Laravel Pint code style enforcement
- Rector automated refactoring
- Pest test suite with full coverage
- GitHub Actions CI/CD pipeline

### Removed
- Support for PHP 8.0 and 8.1
- Support for Laravel 9
- Psalm configuration (replaced by PHPStan/Larastan)
- `composer.lock` from repository

## [3.0.0] - 2022-03-15

- Support for Laravel 9

## [2.0.1] - 2021-04-30

- Bumps laravel/framework from 8.29.0 to 8.40.0

## [2.0.0] - 2021-04-08

- BREAKING CHANGE: Drop support for laravel/framework < 7.0
- Allow use of custom resolvers through MailGunSenderPropertiesResolver
- Allow use of custom mailer names (still defaults to "mailgun")

## [1.0.1] - 2021-03-02

- Fix incomplete README.md
- Fix initial release date mentioned in the changelog

## [1.0.0] - 2021-03-02

- Initial release
