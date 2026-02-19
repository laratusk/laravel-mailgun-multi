<?php

declare(strict_types=1);

namespace Laratusk\LaravelMailgunMulti\Contracts;

use Laratusk\LaravelMailgunMulti\DataObjects\MailgunSenderProperties;

interface MailgunSenderPropertiesResolver
{
    public function resolve(string $senderDomain): MailgunSenderProperties;
}
