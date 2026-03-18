<?php

/**
 * @author Tomáš Chochola <tomaschochola@tomaschochola.cz>
 * @copyright © 2026 Tomáš Chochola <tomaschochola@tomaschochola.cz>
 *
 * @license CC-BY-ND-4.0
 *
 * @see {@link https://creativecommons.org/licenses/by-nd/4.0/} License
 * @see {@link https://github.com/tomaschochola} GitHub Profile
 * @see {@link https://github.com/sponsors/tomaschochola} GitHub Sponsors
 */

declare(strict_types=1);

namespace TomasChochola\Quickmux\Psr\Http\Client;

use IteratorAggregate;
use NoDiscard;
use Override;
use Psr\Http\Client\ClientInterface;
use TomasChochola\Psr\Container\SingletonResolver;
use TomasChochola\Psr\Http\Client\CurlHttpClient;
use Traversable;

/**
 * @no-named-arguments
 *
 * @implements IteratorAggregate<mixed, mixed>
 */
readonly class PsrHttpClientManifest implements IteratorAggregate
{
    #[NoDiscard]
    #[Override]
    public function getIterator(): Traversable
    {
        yield CurlHttpClient::class => new SingletonResolver([CurlHttpClient::class, 'inject']);

        yield ClientInterface::class => new SingletonResolver([CurlHttpClient::class, 'inject']);
    }
}
