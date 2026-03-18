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

namespace TomasChochola\Quickmux\Psr\Log;

use IteratorAggregate;
use NoDiscard;
use Override;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Traversable;

/**
 * @no-named-arguments
 *
 * @implements IteratorAggregate<mixed, mixed>
 */
readonly class PsrLoggerManifest implements IteratorAggregate
{
    #[NoDiscard]
    #[Override]
    public function getIterator(): Traversable
    {
        yield RecorderInterface::class => [Recorder::class, 'inject'];

        yield FormatterInterface::class => [JsonFormatter::class, 'inject'];

        yield WriterInterface::class => [ResourceWriter::class, 'inject'];

        yield ExporterInterface::class => [FormatterWriterExporter::class, 'inject'];

        yield LoggerInterface::class => [Logger::class, 'inject'];
    }
}
