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

namespace TomasChochola\Quickmux\Psr\Http\RequestHandlers;

use IteratorAggregate;
use NoDiscard;
use Override;
use Psr\Http\Server\RequestHandlerInterface;
use TomasChochola\Psr\Container\SingletonResolver;
use TomasChochola\Psr\Http\RequestHandlers\ErrorHandlerMiddleware;
use TomasChochola\Psr\Http\RequestHandlers\ExceptionHandlerMiddleware;
use TomasChochola\Psr\Http\RequestHandlers\JsonEncoder;
use TomasChochola\Psr\Http\RequestHandlers\JsonResponder;
use TomasChochola\Psr\Http\RequestHandlers\JsonWriter;
use TomasChochola\Psr\Http\RequestHandlers\NoContentRequestHandler;
use TomasChochola\Psr\Http\RequestHandlers\NotFoundRequestHandler;
use TomasChochola\Psr\Http\RequestHandlers\NullMiddleware;
use TomasChochola\Psr\Http\RequestHandlers\OkRequestHandler;
use TomasChochola\Psr\Http\RequestHandlers\PipelineResolver;
use TomasChochola\Psr\Http\RequestHandlers\ResponseEmitter;
use TomasChochola\Psr\Http\RequestHandlers\RouteMatcher;
use TomasChochola\Psr\Http\RequestHandlers\RouteRequestHandler;
use TomasChochola\Psr\Http\RequestHandlers\StreamWriter;
use TomasChochola\Psr\Http\RequestHandlers\WithRequestCookiesMiddleware;
use TomasChochola\Psr\Http\RequestHandlers\WithRequestHeadersMiddleware;
use TomasChochola\Psr\Http\RequestHandlers\WithRequestPayloadMiddleware;
use TomasChochola\Psr\Http\RequestHandlers\WithRequestQueryMiddleware;
use Traversable;

/**
 * @no-named-arguments
 *
 * @implements IteratorAggregate<mixed, mixed>
 */
readonly class PsrRequestHandlersManifest implements IteratorAggregate
{
    #[NoDiscard]
    #[Override]
    public function getIterator(): Traversable
    {
        yield ErrorHandlerMiddleware::class => new SingletonResolver([ErrorHandlerMiddleware::class, 'inject']);

        yield ExceptionHandlerMiddleware::class => new SingletonResolver([ExceptionHandlerMiddleware::class, 'inject']);

        yield JsonEncoder::class => new SingletonResolver([JsonEncoder::class, 'inject']);

        yield JsonWriter::class => new SingletonResolver([JsonWriter::class, 'inject']);

        yield JsonResponder::class => new SingletonResolver([JsonResponder::class, 'inject']);

        yield NoContentRequestHandler::class => new SingletonResolver([NoContentRequestHandler::class, 'inject']);

        yield NotFoundRequestHandler::class => new SingletonResolver([NotFoundRequestHandler::class, 'inject']);

        yield NullMiddleware::class => new SingletonResolver([NullMiddleware::class, 'inject']);

        yield OkRequestHandler::class => new SingletonResolver([OkRequestHandler::class, 'inject']);

        yield PipelineResolver::class => new SingletonResolver([PipelineResolver::class, 'inject']);

        yield ResponseEmitter::class => new SingletonResolver([ResponseEmitter::class, 'inject']);

        yield RouteMatcher::class => new SingletonResolver([RouteMatcher::class, 'inject']);

        yield RouteRequestHandler::class => new SingletonResolver([RouteRequestHandler::class, 'inject']);

        yield RequestHandlerInterface::class => new SingletonResolver([RouteRequestHandler::class, 'inject']);

        yield StreamWriter::class => new SingletonResolver([StreamWriter::class, 'inject']);

        yield WithRequestCookiesMiddleware::class => new SingletonResolver([WithRequestCookiesMiddleware::class, 'inject']);

        yield WithRequestHeadersMiddleware::class => new SingletonResolver([WithRequestHeadersMiddleware::class, 'inject']);

        yield WithRequestPayloadMiddleware::class => new SingletonResolver([WithRequestPayloadMiddleware::class, 'inject']);

        yield WithRequestQueryMiddleware::class => new SingletonResolver([WithRequestQueryMiddleware::class, 'inject']);
    }
}
