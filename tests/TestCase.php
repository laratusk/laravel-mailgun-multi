<?php

declare(strict_types=1);

namespace Laratusk\LaravelMailgunMulti\Tests;

use Laratusk\LaravelMailgunMulti\Providers\MailgunMultiServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            MailgunMultiServiceProvider::class,
        ];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('mail.default', 'mailgun');
        $app['config']->set('mail.mailers.mailgun', [
            'transport' => 'mailgun',
        ]);
        $app['config']->set('services.mailgun', [
            'domain' => 'mg.default.com',
            'secret' => 'default-secret-key',
            'endpoint' => 'api.mailgun.net',
            'domains' => [
                'custom.com' => [
                    'domain' => 'mg.custom.com',
                    'secret' => 'custom-secret',
                    'endpoint' => 'api.eu.mailgun.net',
                ],
                'partial.com' => [
                    'secret' => 'partial-secret',
                ],
            ],
        ]);
    }
}
