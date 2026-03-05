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
use TomasChochola\Psr\Clock\NowClock;
use TomasChochola\Psr\Container\CallableResolver;
use TomasChochola\Psr\Container\RegistrarInterface;
use TomasChochola\Psr\Http\Client\CurlHttpClient;
use TomasChochola\Psr\Http\Factory\RequestFactory;
use TomasChochola\Psr\Http\Factory\ResponseFactory;
use TomasChochola\Psr\Http\Factory\ServerRequestFactory;
use TomasChochola\Psr\Http\Factory\StreamFactory;
use TomasChochola\Psr\Http\Factory\UriFactory;
use Traversable;

/**
 * @no-named-arguments
 */
readonly class QuickmuxRegistrar implements RegistrarInterface
{
    #[NoDiscard]
    #[Override]
    public function getIterator(): Traversable
    {
        yield ClientInterface::class => new CallableResolver([CurlHttpClient::class, 'provide']);

        yield ClockInterface::class => new CallableResolver([NowClock::class, 'provide']);

        yield RequestFactoryInterface::class => new CallableResolver([RequestFactory::class, 'provide']);

        yield ResponseFactoryInterface::class => new CallableResolver([ResponseFactory::class, 'provide']);

        yield ServerRequestFactoryInterface::class => new CallableResolver([ServerRequestFactory::class, 'provide']);

        yield ServerRequestInterface::class => new CallableResolver([ServerRequestFactory::class, 'fromGlobals']);

        yield StreamFactoryInterface::class => new CallableResolver([StreamFactory::class, 'provide']);

        yield UriFactoryInterface::class => new CallableResolver([UriFactory::class, 'provide']);
    }
}
