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

namespace TomasChochola\Loaders;

use IteratorAggregate;
use Override;
use Traversable;

use function getenv;
use function is_int;

/**
 * @implements IteratorAggregate<int|string, string>
 *
 * @no-named-arguments
 */
readonly class EnvLoader implements IteratorAggregate
{
    /**
     * @var iterable<int|string, string>
     */
    protected readonly iterable $keys;

    /**
     * @param iterable<int|string, string> $keys
     */
    public function __construct(iterable $keys)
    {
        $this->keys = $keys;
    }

    #[Override]
    public function getIterator(): Traversable
    {
        foreach ($this->keys as $key => $alias) {
            if (is_int($key)) {
                $value = getenv($alias);

                if ($value === false) {
                    continue;
                }

                yield $alias => $value;
            } else {
                $value = getenv($key);

                if ($value === false) {
                    continue;
                }

                yield $alias => $value;
            }
        }
    }
}
