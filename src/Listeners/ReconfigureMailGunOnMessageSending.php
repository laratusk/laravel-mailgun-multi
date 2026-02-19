<?php

declare(strict_types=1);

namespace Laratusk\LaravelMailgunMulti\Listeners;

use Illuminate\Mail\Events\MessageSending;
use Illuminate\Mail\Mailer;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Laratusk\LaravelMailgunMulti\Contracts\MailgunSenderPropertiesResolver;
use Symfony\Component\Mime\Address;

final class ReconfigureMailgunOnMessageSending
{
    public function __construct(
        private readonly MailgunSenderPropertiesResolver $resolver,
        private readonly string $mailerName = 'mailgun',
    ) {}

    public function handle(MessageSending $event): void
    {
        if (! $this->shouldReconfigure()) {
            return;
        }

        $senderDomain = $this->extractSenderDomain($event);

        if ($senderDomain === null) {
            return;
        }

        $properties = $this->resolver->resolve($senderDomain);

        $this->reconfigureTransport($properties->domain, $properties->secret, $properties->endpoint);

        if (Config::get('services.mailgun.log_domain_switches', false)) {
            Log::debug('Mailgun transport reconfigured', [
                'sender_domain' => $senderDomain,
                'mailgun_domain' => $properties->domain,
                'endpoint' => $properties->endpoint,
            ]);
        }
    }

    /**
     * Checks whether the current default mailer uses the mailgun transport.
     */
    private function shouldReconfigure(): bool
    {
        $defaultMailer = Config::get('mail.default');
        $transport = Config::get("mail.mailers.{$defaultMailer}.transport");

        if ($transport !== 'mailgun') {
            $mailerTransport = Config::get("mail.mailers.{$this->mailerName}.transport");

            return $mailerTransport === 'mailgun';
        }

        return true;
    }

    /**
     * Extracts the sender domain from the outgoing message.
     */
    private function extractSenderDomain(MessageSending $event): ?string
    {
        $from = $event->message->getFrom();

        if (empty($from)) {
            return null;
        }

        /** @var Address $firstSender */
        $firstSender = reset($from);
        $email = $firstSender->getAddress();

        $parts = explode('@', $email);

        if (count($parts) !== 2) {
            return null;
        }

        return $parts[1];
    }

    /**
     * Reconfigures the Mailgun transport with the given domain, secret, and endpoint.
     */
    private function reconfigureTransport(string $domain, string $secret, string $endpoint): void
    {
        /** @var \Illuminate\Mail\MailManager $mailManager */
        $mailManager = app('mail.manager');

        $transport = $mailManager->createSymfonyTransport([
            'transport' => 'mailgun',
            'secret' => $secret,
            'domain' => $domain,
            'endpoint' => $endpoint,
        ]);

        /** @var Mailer $mailer */
        $mailer = Mail::mailer($this->mailerName);
        $mailer->setSymfonyTransport($transport);
    }
}
