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
use Psr\SimpleCache\CacheInterface;
use TomasChochola\Psr\Clock\FixedClock;
use TomasChochola\Psr\Http\RequestHandlers\ErrorCatcherMiddleware;
use TomasChochola\Psr\Http\RequestHandlers\ExceptionCatcherMiddleware;
use TomasChochola\Psr\Http\RequestHandlers\NullMiddleware;
use TomasChochola\Psr\Http\RequestHandlers\ThrowableCatcherMiddleware;
use TomasChochola\Psr\Log\ExporterInterface;
use TomasChochola\Psr\Log\Interpolator;
use TomasChochola\Psr\Log\Contextor;
use TomasChochola\Psr\Log\CollectingExporter;
use TomasChochola\Psr\SimpleCache\NullSimpleCache;
use Traversable;

/**
 * @no-named-arguments
 *
 * @implements IteratorAggregate<mixed, mixed>
 */
readonly class PsrTestingManifest implements IteratorAggregate
{
    #[NoDiscard]
    #[Override]
    public function getIterator(): Traversable
    {
        yield ClockInterface::class => new FixedClock();

        yield ErrorCatcherMiddleware::class => new NullMiddleware();

        yield ExceptionCatcherMiddleware::class => new NullMiddleware();

        yield ThrowableCatcherMiddleware::class => new NullMiddleware();

        yield ExporterInterface::class => new CollectingExporter();

        yield Interpolator::class => new Interpolator();

        yield Contextor::class => new Contextor();

        yield CacheInterface::class => new NullSimpleCache();
    }
}
