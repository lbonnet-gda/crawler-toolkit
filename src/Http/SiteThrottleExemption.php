<?php

declare(strict_types=1);

namespace Lbonnet\CrawlerToolkit\Http;

use Lbonnet\CrawlerToolkit\Robots\RobotsTxtCheckerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Scopes a throttling exemption to the site being crawled: its host is exempt for the duration of the
 * crawl, honoring its own robots.txt Crawl-delay instead of the global delay. When the start URL turns out
 * to redirect to another host (e.g., apex to www), the exemption follows the site there, since every
 * following request goes to that host. Does nothing with a client that doesn't support exemptions.
 */
final class SiteThrottleExemption
{
    private ?string $host = null;

    private function __construct(
        private readonly ?ThrottleExemptionInterface $client,
        private readonly ?RobotsTxtCheckerInterface $robotsTxtChecker,
    ) {
    }

    public static function begin(
        HttpClientInterface $httpClient,
        string $siteUrl,
        ?RobotsTxtCheckerInterface $robotsTxtChecker = null,
    ): self {
        $exemption = new self(
            $httpClient instanceof ThrottleExemptionInterface ? $httpClient : null,
            $robotsTxtChecker,
        );
        $exemption->moveTo($siteUrl);

        return $exemption;
    }

    /**
     * Exempts the host of $url instead of the current one. Does nothing when that host is already exempt.
     */
    public function moveTo(string $url): void
    {
        $host = parse_url($url, PHP_URL_HOST);

        if ($this->client === null || !is_string($host) || $host === '') {
            return;
        }

        if ($this->host !== null && strcasecmp($host, $this->host) === 0) {
            return;
        }

        $crawlDelay = $this->robotsTxtChecker?->crawlDelay($url);
        $this->client->setHostDelay($host, $crawlDelay !== null ? (int)round($crawlDelay * 1000) : 0);
        $this->host = $host;
    }

    /**
     * Clears the exemption, so the host is throttled like any other again.
     */
    public function end(): void
    {
        if ($this->client === null || $this->host === null) {
            return;
        }

        $this->client->setHostDelay(null);
        $this->host = null;
    }
}
