<?php

declare(strict_types=1);

use Laratusk\LaravelMailgunMulti\DataObjects\MailgunSenderProperties;

test('it creates sender properties with all values', function () {
    $properties = new MailgunSenderProperties(
        domain: 'mg.example.com',
        secret: 'my-secret',
        endpoint: 'api.mailgun.net',
    );

    expect($properties->domain)->toBe('mg.example.com')
        ->and($properties->secret)->toBe('my-secret')
        ->and($properties->endpoint)->toBe('api.mailgun.net');
});

test('it is immutable', function () {
    $properties = new MailgunSenderProperties(
        domain: 'mg.example.com',
        secret: 'my-secret',
        endpoint: 'api.mailgun.net',
    );

    // readonly class — properties cannot be modified
    expect(fn () => $properties->domain = 'changed')->toThrow(\Error::class);
});
