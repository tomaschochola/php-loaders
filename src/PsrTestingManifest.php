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
use Override;
use TomasChochola\Quickmux\Psr\Clock\PsrClockTestingManifest;
use TomasChochola\Quickmux\Psr\Http\RequestHandlers\PsrRequestHandlersTestingManifest;
use TomasChochola\Quickmux\Psr\Log\PsrLoggerTestingManifest;
use TomasChochola\Quickmux\Psr\SimpleCache\PsrSimpleCacheTestingManifest;
use Traversable;

/**
 * @no-named-arguments
 *
 * @implements IteratorAggregate<mixed, mixed>
 */
readonly class PsrTestingManifest implements IteratorAggregate
{
    #[Override]
    public function getIterator(): Traversable
    {
        yield from new PsrClockTestingManifest();
        yield from new PsrRequestHandlersTestingManifest();
        yield from new PsrLoggerTestingManifest();
        yield from new PsrSimpleCacheTestingManifest();
    }
}
