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
        yield ErrorHandlerMiddleware::class => [ErrorHandlerMiddleware::class, 'inject'];
        yield ExceptionHandlerMiddleware::class => [ExceptionHandlerMiddleware::class, 'inject'];
        yield JsonEncoder::class => [JsonEncoder::class, 'inject'];
        yield JsonWriter::class => [JsonWriter::class, 'inject'];
        yield JsonResponder::class => [JsonResponder::class, 'inject'];
        yield NoContentRequestHandler::class => [NoContentRequestHandler::class, 'inject'];
        yield NotFoundRequestHandler::class => [NotFoundRequestHandler::class, 'inject'];
        yield NullMiddleware::class => [NullMiddleware::class, 'inject'];
        yield OkRequestHandler::class => [OkRequestHandler::class, 'inject'];
        yield PipelineResolver::class => [PipelineResolver::class, 'inject'];
        yield ResponseEmitter::class => [ResponseEmitter::class, 'inject'];
        yield ResponseExiter::class => [ResponseExiterAssembler::class, 'assemble'];
        yield RouteLoader::class => [RouteLoader::class, 'inject'];
        yield RouteMatch::class => [RouteMatch::class, 'inject'];
        yield RouteParams::class => [RouteParams::class, 'inject'];
        yield RouteMatcher::class => [RouteMatcher::class, 'inject'];
        yield RouteSettings::class => [RouteSettings::class, 'inject'];
        yield RouteRequestHandler::class => [RouteRequestHandler::class, 'inject'];
        yield RequestHandlerInterface::class => [RouteRequestHandler::class, 'inject'];
        yield StreamWriter::class => [StreamWriter::class, 'inject'];
        yield WithRequestCookiesMiddleware::class => [WithRequestCookiesMiddleware::class, 'inject'];
        yield WithRequestHeadersMiddleware::class => [WithRequestHeadersMiddleware::class, 'inject'];
        yield WithRequestPayloadMiddleware::class => [WithRequestPayloadMiddleware::class, 'inject'];
        yield WithRequestQueryMiddleware::class => [WithRequestQueryMiddleware::class, 'inject'];
    }
}
