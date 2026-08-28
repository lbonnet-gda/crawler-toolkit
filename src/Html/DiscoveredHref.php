<?php

declare(strict_types=1);

namespace Lbonnet\CrawlerToolkit\Html;

final class DiscoveredHref
{
    public function __construct(
        public readonly string $url,
        public readonly string $anchorText,
        public readonly bool $isExternal,
    ) {
    }
}
