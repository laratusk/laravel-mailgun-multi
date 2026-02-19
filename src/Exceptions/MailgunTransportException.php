<?php

declare(strict_types=1);

namespace Laratusk\LaravelMailgunMulti\Exceptions;

use RuntimeException;

final class MailgunTransportException extends RuntimeException
{
    public static function notMailgunTransport(string $transportClass): self
    {
        return new self("Expected Mailgun transport, got [{$transportClass}]. Ensure your default mailer uses the mailgun transport.");
    }

    public static function reconfigurationFailed(string $domain, string $reason): self
    {
        return new self("Failed to reconfigure Mailgun transport for domain [{$domain}]: {$reason}.");
    }
}
