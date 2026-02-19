<?php

declare(strict_types=1);

namespace Laratusk\LaravelMailgunMulti\Exceptions;

use InvalidArgumentException;

final class InvalidMailgunConfigException extends InvalidArgumentException
{
    public static function missingDomain(string $senderDomain): self
    {
        return new self("Mailgun domain could not be resolved for sender domain: [{$senderDomain}].");
    }

    public static function missingSecret(): self
    {
        return new self('Mailgun secret is not configured. Please set MAILGUN_SECRET in your .env or services.mailgun.secret in config.');
    }

    public static function missingEndpoint(): self
    {
        return new self('Mailgun endpoint is not configured. Please set MAILGUN_ENDPOINT in your .env or services.mailgun.endpoint in config.');
    }

    public static function invalidDomainConfig(string $domain, string $reason): self
    {
        return new self("Invalid Mailgun configuration for domain [{$domain}]: {$reason}.");
    }
}
