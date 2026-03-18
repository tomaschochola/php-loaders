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
use TomasChochola\Psr\Container\SingletonResolver;
use TomasChochola\Psr\Log\ExporterInterface;
use TomasChochola\Psr\Log\FormatterInterface;
use TomasChochola\Psr\Log\FormatterWriterExporter;
use TomasChochola\Psr\Log\JsonFormatter;
use TomasChochola\Psr\Log\Logger;
use TomasChochola\Psr\Log\Recorder;
use TomasChochola\Psr\Log\RecorderInterface;
use TomasChochola\Psr\Log\ResourceWriter;
use TomasChochola\Psr\Log\WriterInterface;
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
        yield RecorderInterface::class => new SingletonResolver([Recorder::class, 'inject']);

        yield FormatterInterface::class => new SingletonResolver([JsonFormatter::class, 'inject']);

        yield WriterInterface::class => new SingletonResolver([ResourceWriter::class, 'inject']);

        yield ExporterInterface::class => new SingletonResolver([FormatterWriterExporter::class, 'inject']);

        yield LoggerInterface::class => new SingletonResolver([Logger::class, 'inject']);
    }
}
