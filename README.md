# CrawlerToolkit

Shared, framework-agnostic building blocks for polite and safe web crawlers. Extracted out
of [lbonnet/link-checker-bundle](https://github.com/lbonnet-gda/link-checker-bundle)
and [lbonnet/on-page-seo-bundle](https://github.com/lbonnet-gda/on-page-seo-bundle) once both bundles needed the same
crawling infrastructure — this package holds only what's genuinely shared, never SEO- or link-specific domain logic,
which stays in each bundle.

This is not a Symfony bundle: it's a plain library of standalone classes. Each consuming bundle wires them into its own
service container itself.

## Components

- **`Http\ThrottledHttpClient`** — `HttpClientInterface` decorator enforcing a minimum delay between consecutive
  requests to the same host, with a `ThrottleExemptionInterface` to temporarily exempt one host (typically the site
  currently being audited).
- **`Http\BoundedContentReader`** — reads an HTTP response body up to a byte cap, cancelling the request past that
  instead of buffering an unbounded (or malicious) response in memory.
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

## License

MIT — see [LICENSE](LICENSE).
