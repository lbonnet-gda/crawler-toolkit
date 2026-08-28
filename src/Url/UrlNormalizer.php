<?php

declare(strict_types=1);

namespace Lbonnet\CrawlerToolkit\Url;

final class UrlNormalizer
{
    /**
     * Normalizes the scheme to avoid crawling/visiting the same page twice just because
     * it's reachable via both http:// and https://. The URL actually requested is left untouched.
     */
    public static function normalizeForDedup(string $url): string
    {
        return preg_replace('#^http://#i', 'https://', $url, 1) ?? $url;
    }
}
