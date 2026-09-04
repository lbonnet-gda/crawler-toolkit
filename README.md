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
- **`Http\BoundedContentReader`** — reads an HTTP response body up to a byte cap, cancelling the request past that
  instead of buffering an unbounded (or malicious) response in memory.
- **`Http\EffectiveUrlResolver`** — resolves the URL a response was ultimately served from after any redirects the HTTP
  client already followed, so relative links in the body resolve against the right page.
- **`Robots\RobotsTxtChecker`** — fetches and parses a host's `robots.txt`, exposing `isAllowed(url)` and
  `crawlDelay(url)` for a configured user agent (`Allow`/`Disallow`/`Crawl-delay` directives, most-specific-rule-wins
  matching, wildcard and `$` end-anchors).
- **`Html\LinkDiscoverer`** — parses `<a href>` elements out of an HTML document into a `list<DiscoveredHref>`(absolute
  URL, anchor text, internal/external), handling relative URL resolution, `<base href>`, fragment stripping, ignored
  schemes (`mailto:`, `tel:`, ...), and exclusion regex patterns.
- **`Url\UrlNormalizer`** — `normalizeForDedup()` treats `http://` and `https://` variants of the same URL as the same
  page for visited-set tracking, without changing the URL actually requested.

## Requirements

- PHP >= 8.1
- Symfony 6.4, 7.4, or 8.1

## Installation

```bash
composer require lbonnet/crawler-toolkit
```

## Security

To report a vulnerability, please don't open a public issue — see [SECURITY.md](SECURITY.md) for how to report it
privately.

## License

MIT — see [LICENSE](LICENSE).
