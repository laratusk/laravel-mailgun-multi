<?php

declare(strict_types=1);

namespace Laratusk\LaravelMailgunMulti\Providers;

use Illuminate\Mail\Events\MessageSending;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Laratusk\LaravelMailgunMulti\Contracts\MailgunSenderPropertiesResolver;
use Laratusk\LaravelMailgunMulti\Listeners\ReconfigureMailgunOnMessageSending;
use Laratusk\LaravelMailgunMulti\Resolvers\ConfigBasedMailgunSenderPropertiesResolver;

final class MailgunMultiServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            MailgunSenderPropertiesResolver::class,
            ConfigBasedMailgunSenderPropertiesResolver::class,
        );
    }

    public function boot(): void
    {
        Event::listen(
            MessageSending::class,
            ReconfigureMailgunOnMessageSending::class,
        );
    }
}
