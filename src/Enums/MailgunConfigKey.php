<?php

declare(strict_types=1);

namespace Laratusk\LaravelMailgunMulti\Enums;

enum MailgunConfigKey: string
{
    case Domain = 'domain';
    case Secret = 'secret';
    case Endpoint = 'endpoint';
}
