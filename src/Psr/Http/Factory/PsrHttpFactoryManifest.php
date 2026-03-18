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

namespace TomasChochola\Quickmux\Psr\Http\Factory;

use IteratorAggregate;
use NoDiscard;
use Override;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;
use TomasChochola\Psr\Container\SingletonResolver;
use TomasChochola\Psr\Http\Factory\CgiServerRequestFactory;
use TomasChochola\Psr\Http\Factory\RequestFactory;
use TomasChochola\Psr\Http\Factory\ResponseFactory;
use TomasChochola\Psr\Http\Factory\ServerRequestFactory;
use TomasChochola\Psr\Http\Factory\StreamFactory;
use TomasChochola\Psr\Http\Factory\UriFactory;
use Traversable;

/**
 * @no-named-arguments
 *
 * @implements IteratorAggregate<mixed, mixed>
 */
readonly class PsrHttpFactoryManifest implements IteratorAggregate
{
    #[NoDiscard]
    #[Override]
    public function getIterator(): Traversable
    {
        yield CgiServerRequestFactory::class => new SingletonResolver([CgiServerRequestFactory::class, 'inject']);

        yield RequestFactory::class => new SingletonResolver([RequestFactory::class, 'inject']);

        yield RequestFactoryInterface::class => new SingletonResolver([RequestFactory::class, 'inject']);

        yield ResponseFactory::class => new SingletonResolver([ResponseFactory::class, 'inject']);

        yield ResponseFactoryInterface::class => new SingletonResolver([ResponseFactory::class, 'inject']);

        yield ServerRequestFactory::class => new SingletonResolver([ServerRequestFactory::class, 'inject']);

        yield ServerRequestFactoryInterface::class => new SingletonResolver([ServerRequestFactory::class, 'inject']);

        yield ServerRequestInterface::class => new SingletonResolver([CgiServerRequestFactory::class, 'produce']);

        yield StreamFactory::class => new SingletonResolver([StreamFactory::class, 'inject']);

        yield StreamFactoryInterface::class => new SingletonResolver([StreamFactory::class, 'inject']);

        yield UriFactory::class => new SingletonResolver([UriFactory::class, 'inject']);

        yield UriFactoryInterface::class => new SingletonResolver([UriFactory::class, 'inject']);
    }
}
