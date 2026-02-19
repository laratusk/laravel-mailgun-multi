# Changelog

All notable changes will be documented in this file.

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
