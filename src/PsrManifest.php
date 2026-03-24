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

use IteratorAggregate;
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
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;
use Psr\SimpleCache\CacheInterface;
use TomasChochola\Psr\Clock\FixedClock;
use TomasChochola\Psr\Clock\NowClock;
use TomasChochola\Psr\Container\SingletonResolver;
use TomasChochola\Psr\Http\Client\CurlHttpClient;
use TomasChochola\Psr\Http\Factory\CgiServerRequestFactory;
use TomasChochola\Psr\Http\Factory\RequestFactory;
use TomasChochola\Psr\Http\Factory\ResponseFactory;
use TomasChochola\Psr\Http\Factory\ServerRequestFactory;
use TomasChochola\Psr\Http\Factory\StreamFactory;
use TomasChochola\Psr\Http\Factory\UriFactory;
use TomasChochola\Psr\Http\RequestHandlers\AfterPipeline;
use TomasChochola\Psr\Http\RequestHandlers\BeforePipeline;
use TomasChochola\Psr\Http\RequestHandlers\ErrorCatcherMiddleware;
use TomasChochola\Psr\Http\RequestHandlers\ErrorLoggerMiddleware;
use TomasChochola\Psr\Http\RequestHandlers\ErrorRaiserMiddleware;
use TomasChochola\Psr\Http\RequestHandlers\ExceptionCatcherMiddleware;
use TomasChochola\Psr\Http\RequestHandlers\ExceptionLoggerMiddleware;
use TomasChochola\Psr\Http\RequestHandlers\JsonEncoder;
use TomasChochola\Psr\Http\RequestHandlers\JsonResponder;
use TomasChochola\Psr\Http\RequestHandlers\JsonWriter;
use TomasChochola\Psr\Http\RequestHandlers\NoContentRequestHandler;
use TomasChochola\Psr\Http\RequestHandlers\NotFoundRequestHandler;
use TomasChochola\Psr\Http\RequestHandlers\NullMiddleware;
use TomasChochola\Psr\Http\RequestHandlers\OkRequestHandler;
use TomasChochola\Psr\Http\RequestHandlers\PipelineResolver;
use TomasChochola\Psr\Http\RequestHandlers\RequireParsedBodyMiddleware;
use TomasChochola\Psr\Http\RequestHandlers\ResponseEmitter;
use TomasChochola\Psr\Http\RequestHandlers\RouteMatcher;
use TomasChochola\Psr\Http\RequestHandlers\RouteRequestHandler;
use TomasChochola\Psr\Http\RequestHandlers\StreamWriter;
use TomasChochola\Psr\Http\RequestHandlers\ThrowableCatcherMiddleware;
use TomasChochola\Psr\Http\RequestHandlers\ThrowableLoggerMiddleware;
use TomasChochola\Psr\Http\RequestHandlers\WithRequestCookiesMiddleware;
use TomasChochola\Psr\Http\RequestHandlers\WithRequestFormMiddleware;
use TomasChochola\Psr\Http\RequestHandlers\WithRequestHeadersMiddleware;
use TomasChochola\Psr\Http\RequestHandlers\WithRequestJsonMiddleware;
use TomasChochola\Psr\Http\RequestHandlers\WithRequestQueryMiddleware;
use TomasChochola\Psr\Log\CollectingExporter;
use TomasChochola\Psr\Log\ExporterInterface;
use TomasChochola\Psr\Log\FormatterInterface;
use TomasChochola\Psr\Log\FormatterWriterExporter;
use TomasChochola\Psr\Log\Interpolator;
use TomasChochola\Psr\Log\InterpolatorInterface;
use TomasChochola\Psr\Log\JsonFormatter;
use TomasChochola\Psr\Log\Logger;
use TomasChochola\Psr\Log\Recorder;
use TomasChochola\Psr\Log\RecorderInterface;
use TomasChochola\Psr\Log\ResourceWriter;
use TomasChochola\Psr\Log\WriterInterface;
use TomasChochola\Psr\SimpleCache\ApcuSimpleCache;
use TomasChochola\Psr\SimpleCache\NullSimpleCache;
use Traversable;

/**
 * @no-named-arguments
 *
 * @implements IteratorAggregate<mixed, mixed>
 */
readonly class PsrManifest implements IteratorAggregate
{
    #[NoDiscard]
    #[Override]
    public function getIterator(): Traversable
    {
        yield FixedClock::class => new SingletonResolver([FixedClock::class, 'inject']);

        yield NowClock::class => new SingletonResolver([NowClock::class, 'inject']);

        yield ClockInterface::class => new SingletonResolver([NowClock::class, 'inject']);

        yield CurlHttpClient::class => new SingletonResolver([CurlHttpClient::class, 'inject']);

        yield ClientInterface::class => new SingletonResolver([CurlHttpClient::class, 'inject']);

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

        yield ErrorRaiserMiddleware::class => new SingletonResolver([ErrorRaiserMiddleware::class, 'inject']);

        yield ErrorCatcherMiddleware::class => new SingletonResolver([ErrorCatcherMiddleware::class, 'inject']);

        yield ExceptionCatcherMiddleware::class => new SingletonResolver([ExceptionCatcherMiddleware::class, 'inject']);

        yield ThrowableCatcherMiddleware::class => new SingletonResolver([ThrowableCatcherMiddleware::class, 'inject']);

        yield ErrorLoggerMiddleware::class => new SingletonResolver([ErrorLoggerMiddleware::class, 'inject']);

        yield ExceptionLoggerMiddleware::class => new SingletonResolver([ExceptionLoggerMiddleware::class, 'inject']);

        yield ThrowableLoggerMiddleware::class => new SingletonResolver([ThrowableLoggerMiddleware::class, 'inject']);

        yield JsonEncoder::class => new SingletonResolver([JsonEncoder::class, 'inject']);

        yield JsonWriter::class => new SingletonResolver([JsonWriter::class, 'inject']);

        yield JsonResponder::class => new SingletonResolver([JsonResponder::class, 'inject']);

        yield NoContentRequestHandler::class => new SingletonResolver([NoContentRequestHandler::class, 'inject']);

        yield NotFoundRequestHandler::class => new SingletonResolver([NotFoundRequestHandler::class, 'inject']);

        yield NullMiddleware::class => new SingletonResolver([NullMiddleware::class, 'inject']);

        yield OkRequestHandler::class => new SingletonResolver([OkRequestHandler::class, 'inject']);

        yield PipelineResolver::class => new SingletonResolver([PipelineResolver::class, 'inject']);

        yield RequireParsedBodyMiddleware::class => new SingletonResolver([RequireParsedBodyMiddleware::class, 'inject']);

        yield ResponseEmitter::class => new SingletonResolver([ResponseEmitter::class, 'inject']);

        yield AfterPipeline::class => new SingletonResolver([AfterPipeline::class, 'inject']);

        yield BeforePipeline::class => new SingletonResolver([BeforePipeline::class, 'inject']);

        yield RouteMatcher::class => new SingletonResolver([RouteMatcher::class, 'inject']);

        yield RouteRequestHandler::class => new SingletonResolver([RouteRequestHandler::class, 'inject']);

        yield RequestHandlerInterface::class => new SingletonResolver([RouteRequestHandler::class, 'inject']);

        yield StreamWriter::class => new SingletonResolver([StreamWriter::class, 'inject']);

        yield WithRequestCookiesMiddleware::class => new SingletonResolver([WithRequestCookiesMiddleware::class, 'inject']);

        yield WithRequestHeadersMiddleware::class => new SingletonResolver([WithRequestHeadersMiddleware::class, 'inject']);

        yield WithRequestFormMiddleware::class => new SingletonResolver([WithRequestFormMiddleware::class, 'inject']);

        yield WithRequestJsonMiddleware::class => new SingletonResolver([WithRequestJsonMiddleware::class, 'inject']);

        yield WithRequestQueryMiddleware::class => new SingletonResolver([WithRequestQueryMiddleware::class, 'inject']);

        yield Recorder::class => new SingletonResolver([Recorder::class, 'inject']);

        yield RecorderInterface::class => new SingletonResolver([Recorder::class, 'inject']);

        yield Interpolator::class => new SingletonResolver([Interpolator::class, 'inject']);

        yield InterpolatorInterface::class => new SingletonResolver([Interpolator::class, 'inject']);

        yield FormatterInterface::class => new SingletonResolver([JsonFormatter::class, 'inject']);

        yield WriterInterface::class => new SingletonResolver([ResourceWriter::class, 'inject']);

        yield ExporterInterface::class => new SingletonResolver([FormatterWriterExporter::class, 'inject']);

        yield CollectingExporter::class => new SingletonResolver([CollectingExporter::class, 'inject']);

        yield LoggerInterface::class => new SingletonResolver([Logger::class, 'inject']);

        yield ApcuSimpleCache::class => new SingletonResolver([ApcuSimpleCache::class, 'inject']);

        yield CacheInterface::class => new SingletonResolver([ApcuSimpleCache::class, 'inject']);

        yield NullSimpleCache::class => new SingletonResolver([NullSimpleCache::class, 'inject']);
    }
}
