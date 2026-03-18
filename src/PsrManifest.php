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
use TomasChochola\Quickmux\Psr\Clock\PsrClockManifest;
use TomasChochola\Quickmux\Psr\Http\Client\PsrHttpClientManifest;
use TomasChochola\Quickmux\Psr\Http\Factory\PsrHttpFactoryManifest;
use TomasChochola\Quickmux\Psr\Http\RequestHandlers\PsrRequestHandlersManifest;
use TomasChochola\Quickmux\Psr\Log\PsrLoggerManifest;
use TomasChochola\Quickmux\Psr\SimpleCache\PsrSimpleCacheManifest;
use Traversable;

/**
 * @no-named-arguments
 *
 * @implements IteratorAggregate<mixed, mixed>
 */
readonly class PsrManifest implements IteratorAggregate
{
    #[Override]
    public function getIterator(): Traversable
    {
        yield from new PsrClockManifest();
        yield from new PsrHttpClientManifest();
        yield from new PsrHttpFactoryManifest();
        yield from new PsrRequestHandlersManifest();
        yield from new PsrLoggerManifest();
        yield from new PsrSimpleCacheManifest();
    }
}
