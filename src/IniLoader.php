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

use DirectoryIterator;
use IteratorAggregate;
use Override;
use Traversable;
use UnexpectedValueException;

use function is_iterable;
use function parse_ini_file;

use const INI_SCANNER_RAW;

/**
 * @no-named-arguments
 *
 * @implements IteratorAggregate<mixed, mixed>
 */
readonly class IniLoader implements IteratorAggregate
{
    private readonly DirectoryIterator $files;

    private readonly bool $processSections;

    private readonly int $scannerMode;

    public function __construct(DirectoryIterator $files, bool $processSections = true, int $scannerMode = INI_SCANNER_RAW)
    {
        $this->files = $files;
        $this->processSections = $processSections;
        $this->scannerMode = $scannerMode;
    }

    #[Override]
    public function getIterator(): Traversable
    {
        foreach ($this->files as $file) {
            $parsed = parse_ini_file((string) $file, $this->processSections, $this->scannerMode);

            if (!is_iterable($parsed)) {
                throw new UnexpectedValueException('parse_ini_file');
            }

            yield from $parsed;
        }
    }
}
