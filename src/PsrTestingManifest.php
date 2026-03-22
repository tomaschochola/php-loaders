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
use TomasChochola\Psr\Http\RequestHandlers\ErrorHandlerMiddleware;
use TomasChochola\Psr\Http\RequestHandlers\NullMiddleware;
use TomasChochola\Psr\Log\ExporterInterface;
use TomasChochola\Psr\Log\TestingExporter;
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

        yield ErrorHandlerMiddleware::class => new NullMiddleware();

        yield ExporterInterface::class => new TestingExporter();

        yield CacheInterface::class => new NullSimpleCache();
    }
}
