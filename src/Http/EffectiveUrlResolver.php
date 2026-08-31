<?php

declare(strict_types=1);

namespace Lbonnet\CrawlerToolkit\Http;

use Symfony\Contracts\HttpClient\ResponseInterface;

final class EffectiveUrlResolver
{
    /**
     * Returns the URL a response was ultimately served from when the HTTP client actually
     * followed one or more redirects, or the originally requested URL otherwise. Redirect
     * count is checked rather than always trusting the "url" info. HTTP clients also
     * report a client-side-normalized "url" (e.g., a trailing slash added to a bare host) even
     * when no redirect happened, which would otherwise be mistaken for a different page.
     */
    public static function resolve(ResponseInterface $response, string $requestedUrl): string
    {
        $redirectCount = $response->getInfo('redirect_count');

        if (!is_int($redirectCount) || $redirectCount === 0) {
            return $requestedUrl;
        }

        $infoUrl = $response->getInfo('url');

        return is_string($infoUrl) && $infoUrl !== '' ? $infoUrl : $requestedUrl;
    }
}
