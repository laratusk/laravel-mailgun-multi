<?php

declare(strict_types=1);

use Laratusk\LaravelMailgunMulti\Exceptions\InvalidMailgunConfigException;

test('missingDomain creates exception with sender domain', function (): void {
    $exception = InvalidMailgunConfigException::missingDomain('acme.com');

    expect($exception->getMessage())->toContain('acme.com');
});

test('missingSecret creates descriptive exception', function (): void {
    $exception = InvalidMailgunConfigException::missingSecret();

    expect($exception->getMessage())->toContain('secret');
});

test('missingEndpoint creates descriptive exception', function (): void {
    $exception = InvalidMailgunConfigException::missingEndpoint();

    expect($exception->getMessage())->toContain('endpoint');
});

test('invalidDomainConfig includes domain and reason', function (): void {
    $exception = InvalidMailgunConfigException::invalidDomainConfig('test.com', 'empty secret');

    expect($exception->getMessage())
        ->toContain('test.com')
        ->toContain('empty secret');
});
