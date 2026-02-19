<?php

declare(strict_types=1);

namespace Laratusk\LaravelMailgunMulti\DataObjects;

final readonly class MailgunSenderProperties
{
    public function __construct(
        public string $domain,
        public string $secret,
        public string $endpoint,
    ) {}
}
