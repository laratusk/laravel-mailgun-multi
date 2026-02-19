<?php

declare(strict_types=1);

namespace Laratusk\LaravelMailgunMulti\Resolvers;

use Illuminate\Support\Facades\Config;
use Laratusk\LaravelMailgunMulti\Contracts\MailgunSenderPropertiesResolver;
use Laratusk\LaravelMailgunMulti\DataObjects\MailgunSenderProperties;
use Laratusk\LaravelMailgunMulti\Exceptions\InvalidMailgunConfigException;

final class ConfigBasedMailgunSenderPropertiesResolver implements MailgunSenderPropertiesResolver
{
    public function resolve(string $senderDomain): MailgunSenderProperties
    {
        $domainConfig = $this->getDomainConfig($senderDomain);

        $domain = $domainConfig['domain'] ?? $this->buildDefaultDomain($senderDomain);
        $secret = $domainConfig['secret'] ?? $this->getGlobalConfig('secret');
        $endpoint = $domainConfig['endpoint'] ?? $this->getGlobalConfig('endpoint', 'api.mailgun.net');

        if ($secret === null || $secret === '') {
            throw InvalidMailgunConfigException::missingSecret();
        }

        if ($endpoint === null || $endpoint === '') {
            throw InvalidMailgunConfigException::missingEndpoint();
        }

        return new MailgunSenderProperties(
            domain: $domain,
            secret: $secret,
            endpoint: $endpoint,
        );
    }

    /**
     * Returns the domain-specific configuration from the services config.
     *
     * @return array<string, string|null>
     */
    private function getDomainConfig(string $senderDomain): array
    {
        /** @var array<string, array<string, string|null>> $domains */
        $domains = Config::get('services.mailgun.domains', []);

        return $domains[$senderDomain] ?? [];
    }

    /**
     * Builds the default Mailgun domain: mg.{senderDomain}
     */
    private function buildDefaultDomain(string $senderDomain): string
    {
        return 'mg.' . $senderDomain;
    }

    /**
     * Retrieves a value from the global mailgun config.
     */
    private function getGlobalConfig(string $key, ?string $default = null): ?string
    {
        /** @var string|null $value */
        $value = Config::get("services.mailgun.{$key}", $default);

        return $value;
    }
}
