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
use IteratorAggregate;
use Psr\Clock\ClockInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;
use TomasChochola\Psr\Clock\NowClock;
use TomasChochola\Psr\Container\CallableCargo;
use TomasChochola\Psr\Http\Client\CurlHttpClient;
use TomasChochola\Psr\Http\Factory\RequestForge;
use TomasChochola\Psr\Http\Factory\ResponseForge;
use TomasChochola\Psr\Http\Factory\ServerRequestForge;
use TomasChochola\Psr\Http\Factory\StreamForge;
use TomasChochola\Psr\Http\Factory\UriForge;
use Traversable;

/**
 * @no-named-arguments
 */
readonly class QuickmuxManifest implements IteratorAggregate
{
    #[NoDiscard]
    #[Override]
    public function getIterator(): Traversable
    {
        yield ClientInterface::class => new CallableCargo([CurlHttpClient::class, 'unload']);

        yield ClockInterface::class => new CallableCargo([NowClock::class, 'unload']);

        yield RequestFactoryInterface::class => new CallableCargo([RequestForge::class, 'unload']);

        yield ResponseFactoryInterface::class => new CallableCargo([ResponseForge::class, 'unload']);

        yield ServerRequestFactoryInterface::class => new CallableCargo([ServerRequestForge::class, 'unload']);

        yield ServerRequestInterface::class => new CallableCargo([ServerRequestForge::class, 'produce']);

        yield StreamFactoryInterface::class => new CallableCargo([StreamForge::class, 'unload']);

        yield UriFactoryInterface::class => new CallableCargo([UriForge::class, 'unload']);
    }
}
