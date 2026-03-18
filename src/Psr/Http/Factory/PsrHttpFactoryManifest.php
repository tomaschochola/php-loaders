<?php

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
        yield CgiServerRequestFactory::class => [CgiServerRequestFactory::class, 'inject'];
        yield RequestFactory::class => [RequestFactory::class, 'inject'];
        yield RequestFactoryInterface::class => [RequestFactory::class, 'inject'];
        yield ResponseFactory::class => [ResponseFactory::class, 'inject'];
        yield ResponseFactoryInterface::class => [ResponseFactory::class, 'inject'];
        yield ServerRequestFactory::class => [ServerRequestFactory::class, 'inject'];
        yield ServerRequestFactoryInterface::class => [ServerRequestFactory::class, 'inject'];
        yield ServerRequestInterface::class => [CgiServerRequestFactory::class, 'create'];
        yield StreamFactory::class => [StreamFactory::class, 'inject'];
        yield StreamFactoryInterface::class => [StreamFactory::class, 'inject'];
        yield UriFactory::class => [UriFactory::class, 'inject'];
        yield UriFactoryInterface::class => [UriFactory::class, 'inject'];
    }
}
