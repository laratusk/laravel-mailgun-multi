<?php

declare(strict_types=1);

use Illuminate\Mail\Events\MessageSending;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Laratusk\LaravelMailgunMulti\Contracts\MailgunSenderPropertiesResolver;
use Laratusk\LaravelMailgunMulti\DataObjects\MailgunSenderProperties;
use Laratusk\LaravelMailgunMulti\Listeners\ReconfigureMailgunOnMessageSending;
use Symfony\Component\Mime\Email;

test('it extracts sender domain and calls resolver', function (): void {
    $resolver = Mockery::mock(MailgunSenderPropertiesResolver::class);
    $resolver->shouldReceive('resolve')
        ->with('acme.com')
        ->once()
        ->andReturn(new MailgunSenderProperties(
            domain: 'mg.acme.com',
            secret: 'acme-secret',
            endpoint: 'api.mailgun.net',
        ));

    $listener = new ReconfigureMailgunOnMessageSending($resolver);

    $email = new Email;
    $email->from('user@acme.com');
    $email->to('recipient@example.com');
    $email->subject('Test');

    $event = new MessageSending(
        message: $email,
        data: [],
    );

    $listener->handle($event);
});

test('it does nothing when from is empty', function (): void {
    $resolver = Mockery::mock(MailgunSenderPropertiesResolver::class);
    $resolver->shouldNotReceive('resolve');

    $listener = new ReconfigureMailgunOnMessageSending($resolver);

    $email = new Email;
    $email->to('recipient@example.com');
    $email->subject('Test');

    $event = new MessageSending(
        message: $email,
        data: [],
    );

    $listener->handle($event);
});

test('it skips when default mailer is not mailgun', function (): void {
    Config::set('mail.default', 'smtp');
    Config::set('mail.mailers.smtp', ['transport' => 'smtp']);
    Config::set('mail.mailers.mailgun', ['transport' => 'mailgun']);

    $resolver = Mockery::mock(MailgunSenderPropertiesResolver::class);

    $listener = new ReconfigureMailgunOnMessageSending($resolver, 'smtp');

    $email = new Email;
    $email->from('user@acme.com');

    $event = new MessageSending(message: $email, data: []);

    $listener->handle($event);

    $resolver->shouldNotHaveReceived('resolve');
});

test('it logs domain switch when logging is enabled', function (): void {
    Config::set('services.mailgun.log_domain_switches', true);

    Log::shouldReceive('debug')
        ->once()
        ->with('Mailgun transport reconfigured', Mockery::on(fn (array $context): bool => $context['sender_domain'] === 'acme.com'
            && $context['mailgun_domain'] === 'mg.acme.com',
        ));

    $resolver = Mockery::mock(MailgunSenderPropertiesResolver::class);
    $resolver->shouldReceive('resolve')
        ->andReturn(new MailgunSenderProperties(
            domain: 'mg.acme.com',
            secret: 'secret',
            endpoint: 'api.mailgun.net',
        ));

    $listener = new ReconfigureMailgunOnMessageSending($resolver);

    $email = new Email;
    $email->from('user@acme.com');

    $event = new MessageSending(message: $email, data: []);

    $listener->handle($event);
});
