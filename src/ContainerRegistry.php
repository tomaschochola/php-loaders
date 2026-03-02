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

namespace TomasChochola\Quickmux;

use NoDiscard;
use Override;
use Psr\Clock\ClockInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;
use TomasChochola\Psr\Clock\NowClockProvider;
use TomasChochola\Psr\Container\CallableResolver;
use TomasChochola\Psr\Container\RegistrarInterface;
use TomasChochola\Psr\Http\Client\CurlHttpClientProvider;
use TomasChochola\Psr\Http\Factory\RequestFactoryProvider;
use TomasChochola\Psr\Http\Factory\ResponseFactoryProvider;
use TomasChochola\Psr\Http\Factory\ServerRequestFactoryProvider;
use TomasChochola\Psr\Http\Factory\ServerRequestProvider;
use TomasChochola\Psr\Http\Factory\StreamFactoryProvider;
use TomasChochola\Psr\Http\Factory\UriFactoryProvider;
use Traversable;

/**
 * @no-named-arguments
 */
readonly class ContainerRegistry implements RegistrarInterface
{
    #[NoDiscard]
    #[Override]
    public function getIterator(): Traversable
    {
        yield ClientInterface::class => new CallableResolver([CurlHttpClientProvider::class, 'provide']);

        yield ClockInterface::class => new CallableResolver([NowClockProvider::class, 'provide']);

        yield RequestFactoryInterface::class => new CallableResolver([RequestFactoryProvider::class, 'provide']);

        yield ResponseFactoryInterface::class => new CallableResolver([ResponseFactoryProvider::class, 'provide']);

        yield ServerRequestFactoryInterface::class => new CallableResolver([ServerRequestFactoryProvider::class, 'provide']);

        yield ServerRequestInterface::class => new CallableResolver([ServerRequestProvider::class, 'provide']);

        yield StreamFactoryInterface::class => new CallableResolver([StreamFactoryProvider::class, 'provide']);

        yield UriFactoryInterface::class => new CallableResolver([UriFactoryProvider::class, 'provide']);
    }
}
