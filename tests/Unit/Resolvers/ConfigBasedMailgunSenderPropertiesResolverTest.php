<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Config;
use Laratusk\LaravelMailgunMulti\Exceptions\InvalidMailgunConfigException;
use Laratusk\LaravelMailgunMulti\Resolvers\ConfigBasedMailgunSenderPropertiesResolver;

beforeEach(function (): void {
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

test('it resolves fully configured domain', function (): void {
    $resolver = new ConfigBasedMailgunSenderPropertiesResolver;
    $properties = $resolver->resolve('custom.com');

    expect($properties->domain)->toBe('mg.custom.com')
        ->and($properties->secret)->toBe('custom-secret')
        ->and($properties->endpoint)->toBe('api.eu.mailgun.net');
});

test('it falls back to global config for partial domain config', function (): void {
    $resolver = new ConfigBasedMailgunSenderPropertiesResolver;
    $properties = $resolver->resolve('partial.com');

    expect($properties->domain)->toBe('mg.partial.com')
        ->and($properties->secret)->toBe('partial-only-secret')
        ->and($properties->endpoint)->toBe('api.mailgun.net');
});

test('it builds default domain for unconfigured domains', function (): void {
    $resolver = new ConfigBasedMailgunSenderPropertiesResolver;
    $properties = $resolver->resolve('unknown.com');

    expect($properties->domain)->toBe('mg.unknown.com')
        ->and($properties->secret)->toBe('default-secret')
        ->and($properties->endpoint)->toBe('api.mailgun.net');
});

test('it throws exception when secret is missing', function (): void {
    Config::set('services.mailgun.secret');

    $resolver = new ConfigBasedMailgunSenderPropertiesResolver;
    $resolver->resolve('unknown.com');
})->throws(InvalidMailgunConfigException::class, 'secret is not configured');

test('it throws exception when secret is empty string', function (): void {
    Config::set('services.mailgun.secret', '');

    $resolver = new ConfigBasedMailgunSenderPropertiesResolver;
    $resolver->resolve('unknown.com');
})->throws(InvalidMailgunConfigException::class, 'secret is not configured');

test('it throws exception when endpoint is missing', function (): void {
    Config::set('services.mailgun.endpoint');

    $resolver = new ConfigBasedMailgunSenderPropertiesResolver;
    $resolver->resolve('unknown.com');
})->throws(InvalidMailgunConfigException::class, 'endpoint is not configured');

test('it throws exception when endpoint is empty string', function (): void {
    Config::set('services.mailgun.endpoint', '');

    $resolver = new ConfigBasedMailgunSenderPropertiesResolver;
    $resolver->resolve('unknown.com');
})->throws(InvalidMailgunConfigException::class, 'endpoint is not configured');

test('it uses domain-specific secret over global secret', function (): void {
    $resolver = new ConfigBasedMailgunSenderPropertiesResolver;
    $properties = $resolver->resolve('custom.com');

    expect($properties->secret)->toBe('custom-secret');
});
