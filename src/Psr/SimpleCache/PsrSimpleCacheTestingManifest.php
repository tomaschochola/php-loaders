<?php

declare(strict_types=1);

namespace TomasChochola\Quickmux\Psr\SimpleCache;

use IteratorAggregate;
use NoDiscard;
use Override;
use Psr\SimpleCache\CacheInterface;
use Traversable;

/**
 * @no-named-arguments
 *
 * @implements IteratorAggregate<mixed, mixed>
 */
readonly class PsrSimpleCacheTestingManifest implements IteratorAggregate
{
    #[NoDiscard]
    #[Override]
    public function getIterator(): Traversable
    {
        yield CacheInterface::class => new NullSimpleCache();
    }
}
