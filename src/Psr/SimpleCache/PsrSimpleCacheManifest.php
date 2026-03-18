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
readonly class PsrSimpleCacheManifest implements IteratorAggregate
{
    #[NoDiscard]
    #[Override]
    public function getIterator(): Traversable
    {
        yield ApcuSimpleCache::class => [ApcuSimpleCache::class, 'inject'];
        yield CacheInterface::class => [ApcuSimpleCache::class, 'inject'];
        yield NullSimpleCache::class => [NullSimpleCache::class, 'inject'];
    }
}
