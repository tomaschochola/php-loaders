<?php

declare(strict_types=1);

namespace TomasChochola\Loaders;

use DirectoryIterator;
use IteratorAggregate;
use Override;
use Traversable;
use UnexpectedValueException;

use function is_iterable;

/**
 * @no-named-arguments
 *
 * @implements IteratorAggregate<mixed, mixed>
 */
readonly class PhpLoader implements IteratorAggregate
{
    private DirectoryIterator $files;

    public function __construct(DirectoryIterator $files)
    {
        $this->files = $files;
    }

    #[Override()]
    public function getIterator(): Traversable
    {
        foreach ($this->files as $file) {
            if (!$file->isFile()) {
                continue;
            }

            $loaded = require $file->getPathname();

            if (!is_iterable($loaded)) {
                throw new UnexpectedValueException('require');
            }

            yield from $loaded;
        }
    }
}
