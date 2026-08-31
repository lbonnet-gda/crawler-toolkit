<?php

declare(strict_types=1);

namespace Lbonnet\CrawlerToolkit\Tests\Http;

use Lbonnet\CrawlerToolkit\Http\EffectiveUrlResolver;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\HttpClient\ResponseInterface;

final class EffectiveUrlResolverTest extends TestCase
{
    public function testResolveReturnsTheRedirectedUrlWhenRedirectsWereFollowed(): void
    {
        $response = $this->createMock(ResponseInterface::class);
        $response->method('getInfo')->willReturnMap([
            ['redirect_count', 1],
            ['url', 'https://example.com/fr'],
        ]);

        $this->assertSame(
            'https://example.com/fr',
            EffectiveUrlResolver::resolve($response, 'https://example.com'),
        );
    }

    public function testResolveIgnoresUrlNormalizationWhenNoRedirectHappened(): void
    {
        // A bare host like "https://example.com" is reported back as "https://example.com/"
        // by HTTP clients even without any redirect; this must not be taken for a different page.
        $response = $this->createMock(ResponseInterface::class);
        $response->method('getInfo')->willReturnMap([
            ['redirect_count', 0],
            ['url', 'https://example.com/'],
        ]);

        $this->assertSame(
            'https://example.com',
            EffectiveUrlResolver::resolve($response, 'https://example.com'),
        );
    }

    public function testResolveFallsBackToTheRequestedUrlWhenRedirectCountIsMissing(): void
    {
        $response = $this->createMock(ResponseInterface::class);
        $response->method('getInfo')->willReturnMap([
            ['redirect_count', null],
            ['url', 'https://example.com/fr'],
        ]);

        $this->assertSame(
            'https://example.com',
            EffectiveUrlResolver::resolve($response, 'https://example.com'),
        );
    }

    public function testResolveFallsBackToTheRequestedUrlWhenInfoUrlIsEmpty(): void
    {
        $response = $this->createMock(ResponseInterface::class);
        $response->method('getInfo')->willReturnMap([
            ['redirect_count', 1],
            ['url', ''],
        ]);

        $this->assertSame(
            'https://example.com',
            EffectiveUrlResolver::resolve($response, 'https://example.com'),
        );
    }
}
