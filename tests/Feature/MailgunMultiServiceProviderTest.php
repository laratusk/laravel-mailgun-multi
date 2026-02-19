<?php

declare(strict_types=1);

use Laratusk\LaravelMailgunMulti\Contracts\MailgunSenderPropertiesResolver;
use Laratusk\LaravelMailgunMulti\DataObjects\MailgunSenderProperties;
use Laratusk\LaravelMailgunMulti\Resolvers\ConfigBasedMailgunSenderPropertiesResolver;

test('it binds the default resolver', function () {
    $resolver = app(MailgunSenderPropertiesResolver::class);

    expect($resolver)->toBeInstanceOf(ConfigBasedMailgunSenderPropertiesResolver::class);
});

test('it allows overriding the resolver binding', function () {
    $custom = new class implements MailgunSenderPropertiesResolver
    {
        public function resolve(string $senderDomain): MailgunSenderProperties
        {
            return new MailgunSenderProperties(
                domain: 'custom-domain',
                secret: 'custom-secret',
                endpoint: 'custom-endpoint',
            );
        }
    };

    app()->bind(MailgunSenderPropertiesResolver::class, fn () => $custom);

    $resolver = app(MailgunSenderPropertiesResolver::class);

    expect($resolver)->toBe($custom);
});
