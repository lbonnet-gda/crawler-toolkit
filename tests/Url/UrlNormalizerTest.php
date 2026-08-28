<?php

declare(strict_types=1);

namespace Lbonnet\CrawlerToolkit\Tests\Url;

use Lbonnet\CrawlerToolkit\Url\UrlNormalizer;
use PHPUnit\Framework\TestCase;

final class UrlNormalizerTest extends TestCase
{
    public function testNormalizesHttpToHttps(): void
    {
        $this->assertSame(
            'https://example.com/page',
            UrlNormalizer::normalizeForDedup('http://example.com/page'),
        );
    }

    public function testLeavesHttpsUnchanged(): void
    {
        $this->assertSame(
            'https://example.com/page',
            UrlNormalizer::normalizeForDedup('https://example.com/page'),
        );
    }

    public function testOnlyReplacesTheSchemeAtTheStart(): void
    {
        $this->assertSame(
            'https://example.com/redirect?url=http://other.com',
            UrlNormalizer::normalizeForDedup('http://example.com/redirect?url=http://other.com'),
        );
    }

    public function testIsCaseInsensitiveOnTheScheme(): void
    {
        $this->assertSame(
            'https://example.com/page',
            UrlNormalizer::normalizeForDedup('HTTP://example.com/page'),
        );
    }
}
