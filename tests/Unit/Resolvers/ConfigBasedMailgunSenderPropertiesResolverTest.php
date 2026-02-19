<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Config;
use Laratusk\LaravelMailgunMulti\Exceptions\InvalidMailgunConfigException;
use Laratusk\LaravelMailgunMulti\Resolvers\ConfigBasedMailgunSenderPropertiesResolver;

beforeEach(function () {
    Config::set('services.mailgun', [
        'domain' => 'mg.default.com',
        'secret' => 'default-secret',
        'endpoint' => 'api.mailgun.net',
        'domains' => [
            'custom.com' => [
                'domain' => 'mg.custom.com',
                'secret' => 'custom-secret',
                'endpoint' => 'api.eu.mailgun.net',
            ],
            'partial.com' => [
                'secret' => 'partial-only-secret',
            ],
        ],
    ]);
});

test('it resolves fully configured domain', function () {
    $resolver = new ConfigBasedMailgunSenderPropertiesResolver;
    $properties = $resolver->resolve('custom.com');

    expect($properties->domain)->toBe('mg.custom.com')
        ->and($properties->secret)->toBe('custom-secret')
        ->and($properties->endpoint)->toBe('api.eu.mailgun.net');
});

test('it falls back to global config for partial domain config', function () {
    $resolver = new ConfigBasedMailgunSenderPropertiesResolver;
    $properties = $resolver->resolve('partial.com');

    expect($properties->domain)->toBe('mg.partial.com')
        ->and($properties->secret)->toBe('partial-only-secret')
        ->and($properties->endpoint)->toBe('api.mailgun.net');
});

test('it builds default domain for unconfigured domains', function () {
    $resolver = new ConfigBasedMailgunSenderPropertiesResolver;
    $properties = $resolver->resolve('unknown.com');

    expect($properties->domain)->toBe('mg.unknown.com')
        ->and($properties->secret)->toBe('default-secret')
        ->and($properties->endpoint)->toBe('api.mailgun.net');
});

test('it throws exception when secret is missing', function () {
    Config::set('services.mailgun.secret', null);

    $resolver = new ConfigBasedMailgunSenderPropertiesResolver;
    $resolver->resolve('unknown.com');
})->throws(InvalidMailgunConfigException::class, 'secret is not configured');

test('it throws exception when secret is empty string', function () {
    Config::set('services.mailgun.secret', '');

    $resolver = new ConfigBasedMailgunSenderPropertiesResolver;
    $resolver->resolve('unknown.com');
})->throws(InvalidMailgunConfigException::class, 'secret is not configured');

test('it uses domain-specific secret over global secret', function () {
    $resolver = new ConfigBasedMailgunSenderPropertiesResolver;
    $properties = $resolver->resolve('custom.com');

    expect($properties->secret)->toBe('custom-secret');
});
