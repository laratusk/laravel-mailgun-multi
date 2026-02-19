# Multiple Mailgun Domains in one Laravel app

![PHP Version](https://img.shields.io/badge/php-%5E8.2-blue)
![Laravel](https://img.shields.io/badge/laravel-10%20%7C%2011%20%7C%2012-red)
![License](https://img.shields.io/badge/license-MIT-green)

Send emails from multiple Mailgun domains in a single Laravel application — **automatic transport reconfiguration** based on the sender's domain. No changes required in calling code.

```php
// Without this package: calling code must select the right mailer manually
Mail::mailer('mailgun-acme')->to('j.doe@example.net')->send($mailable);

// With this package: just send — transport is reconfigured automatically
Mail::to('j.doe@example.net')->send($mailable);
```

## Requirements

- PHP >= 8.2
- Laravel 10, 11, or 12

## Installation

```bash
composer require laratusk/laravel-mailgun-multi
```

Laravel's auto-discovery will register the service provider automatically.

## How it works

This package listens to the `Illuminate\Mail\Events\MessageSending` event, which Laravel dispatches just before sending each email. The listener reads the `from` address, extracts the sender domain, and reconfigures the Mailgun transport accordingly.

This works seamlessly for both direct and queued messages — no changes to your Mailables or controllers needed.

## Usage

### Basic setup

Ensure your `config/services.php` has a mailgun entry:

```php
'mailgun' => [
    'domain'   => env('MAILGUN_DOMAIN'),
    'secret'   => env('MAILGUN_SECRET'),
    'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
],
```

If your sender address is `sales@acme.app`, the package will automatically use `mg.acme.app` as the Mailgun domain.

### Per-domain configuration

Add a `domains` key to override settings for specific sender domains:

```php
'mailgun' => [
    'domain'   => env('MAILGUN_DOMAIN'),
    'secret'   => env('MAILGUN_SECRET'),
    'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    'domains'  => [
        'acme.com' => [
            'domain'   => 'mg.acme.com',
            'secret'   => 'acme-mailgun-secret',
            'endpoint' => 'api.eu.mailgun.net',
        ],
        'awesome.app' => [
            // Only override the secret; domain and endpoint fall back to global defaults
            'secret' => 'awesome-secret',
        ],
    ],
],
```

> If a domain is not listed, the Mailgun domain defaults to `mg.{sender-domain}`.
> Missing `secret` or `endpoint` values fall back to the global mailgun config.

### Optional: enable domain switch logging

```php
'mailgun' => [
    // ...
    'log_domain_switches' => true, // logs debug info on each transport reconfiguration
],
```

### Custom resolver

If the default config-based resolution does not fit your use case, implement the `MailgunSenderPropertiesResolver` contract:

```php
use Laratusk\LaravelMailgunMulti\Contracts\MailgunSenderPropertiesResolver;
use Laratusk\LaravelMailgunMulti\DataObjects\MailgunSenderProperties;

class MyCustomResolver implements MailgunSenderPropertiesResolver
{
    public function resolve(string $senderDomain): MailgunSenderProperties
    {
        // Fetch config from database, cache, or any source
        return new MailgunSenderProperties(
            domain: 'mg.' . $senderDomain,
            secret: 'my-secret',
            endpoint: 'api.mailgun.net',
        );
    }
}
```

Then bind it in a service provider:

```php
use Laratusk\LaravelMailgunMulti\Contracts\MailgunSenderPropertiesResolver;

$this->app->bind(MailgunSenderPropertiesResolver::class, MyCustomResolver::class);
```

## Testing

```bash
composer test
```

Run all quality checks (format + static analysis + tests):

```bash
composer quality
```

## Credits

This package is a maintained fork of [skitlabs/laravel-mailgun-multiple-domains](https://github.com/skitlabs/laravel-mailgun-multiple-domains) by [Jurre Vriezinga](https://github.com/skitlabs), which is no longer actively maintained.

## Contributing

Contributions are welcome. Please open an issue or pull request on [GitHub](https://github.com/laratusk/laravel-mailgun-multi).

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
