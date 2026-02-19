<?php

declare(strict_types=1);

use Laratusk\LaravelMailgunMulti\DataObjects\MailgunSenderProperties;

test('it creates sender properties with all values', function (): void {
    $properties = new MailgunSenderProperties(
        domain: 'mg.example.com',
        secret: 'my-secret',
        endpoint: 'api.mailgun.net',
    );

    expect($properties->domain)->toBe('mg.example.com')
        ->and($properties->secret)->toBe('my-secret')
        ->and($properties->endpoint)->toBe('api.mailgun.net');
});

test('it is immutable', function (): void {
    $properties = new MailgunSenderProperties(
        domain: 'mg.example.com',
        secret: 'my-secret',
        endpoint: 'api.mailgun.net',
    );

    // readonly class — properties cannot be modified
    expect(fn (): string => $properties->domain = 'changed')->toThrow(\Error::class);
});
