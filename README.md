# CrawlerToolkit

[![CI](https://github.com/lbonnet-gda/crawler-toolkit/actions/workflows/ci.yaml/badge.svg)](https://github.com/lbonnet-gda/crawler-toolkit/actions/workflows/ci.yaml)
[![Latest Version](https://img.shields.io/packagist/v/lbonnet/crawler-toolkit.svg)](https://packagist.org/packages/lbonnet/crawler-toolkit)
[![PHP Version](https://img.shields.io/packagist/php-v/lbonnet/crawler-toolkit.svg)](https://packagist.org/packages/lbonnet/crawler-toolkit)
[![License](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE)

Shared, framework-agnostic building blocks for polite and safe web crawlers.

This is not a Symfony bundle: it's a plain library of standalone classes. Each consuming bundle wires them into its own
service container itself.

## Components

- **`Http\ThrottledHttpClient`** — `HttpClientInterface` decorator enforcing a minimum delay between consecutive
  requests to the same host, with a `ThrottleExemptionInterface` to temporarily exempt one host (typically the site
  currently being audited).
- **`Http\SiteThrottleExemption`** — scopes that exemption to the site being crawled: `begin()` exempts the start
  URL's host (honoring its `robots.txt` `Crawl-delay`), `moveTo()` follows the site when the start URL redirects to
  another host (e.g., apex to www), and `end()` clears it. Inert with a client that doesn't support exemptions.
- **`Http\BoundedContentReader`** — reads an HTTP response body up to a byte cap, cancelling the request past that
  instead of buffering an unbounded (or malicious) response in memory.
- **`Http\EffectiveUrlResolver`** — resolves the URL a response was ultimately served from after any redirects the HTTP
  client already followed, so relative links in the body resolve against the right page.
- **`Robots\RobotsTxtChecker`** — fetches and parses a host's `robots.txt` once, exposing `isAllowed(url)` and
  `crawlDelay(url)` for a configured user agent (`Allow`/`Disallow`/`Crawl-delay` directives, wildcard and `$`
  end-anchors). Rules are matched the way Google documents it: only the group naming the most specific user agent
  applies, the longest rule wins, and the least restrictive one wins a tie. Through `RobotsTxtProviderInterface`,
  `robotsTxt(url)` also returns the file itself as a `RobotsTxt`, even when the crawler doesn't honor it: its status as
  Google reads it (`Found`, `NotFound` for a 4xx, `ServerError` for a 5xx, a 429, or a network failure) and
  `isAllowed(url, userAgent)` for any crawler, Googlebot included, plus the `Sitemap:` URLs it declares. Like Google,
  the checker treats a host whose `robots.txt` answers a server error as off-limits: `isSiteBlocked(url)` is then true
  and `isAllowed(url)` false for every URL of that host.
- **`Html\LinkDiscoverer`** — parses `<a href>` elements out of an HTML document into a `list<DiscoveredHref>`(absolute
  URL, anchor text, internal/external), handling relative URL resolution, `<base href>`, fragment stripping, ignored
  schemes (`mailto:`, `tel:`, ...), and exclusion regex patterns.
- **`Url\UrlNormalizer`** — `normalizeForDedup()` treats `http://` and `https://` variants of the same URL as the same
  page for visited-set tracking, without changing the URL actually requested.

## Requirements

- PHP >= 8.1
- Symfony 6.4, 7.x, or 8.x

## Installation

```bash
composer require lbonnet/crawler-toolkit
```

## Security

To report a vulnerability, please don't open a public issue — see [SECURITY.md](SECURITY.md) for how to report it
privately.

## License

MIT — see [LICENSE](LICENSE).
