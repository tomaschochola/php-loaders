<?php

declare(strict_types=1);

namespace TomasChochola\Loaders;

use IteratorAggregate;
use Override;
use Traversable;
use UnexpectedValueException;

use function getenv;
use function is_int;
use function is_string;

/**
 * @implements IteratorAggregate<mixed, string>
 *
 * @no-named-arguments
 */
readonly class EnvLoader implements IteratorAggregate
{
    private bool | null $fallback;

    /**
     * @var iterable<mixed, string>
     */
    private iterable $keys;

    /**
     * @param iterable<mixed, string> $keys
     */
    public function __construct(iterable $keys, bool | null $fallback = null)
    {
        $this->keys = $keys;
        $this->fallback = $fallback;
    }

    #[Override()]
    public function getIterator(): Traversable
    {
        foreach ($this->keys as $key => $alias) {
            if (is_int($key)) {
                $value = getenv($alias);
            } elseif (is_string($key)) {
                $value = getenv($key);
            } else {
                continue;
            }

            if ($value === false) {
                if ($this->fallback === true) {
                    yield $alias => '';
                } elseif ($this->fallback === false) {
                    throw new UnexpectedValueException($alias);
                }
            } else {
                yield $alias => $value;
            }
        }
    }
}
