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

use TomasChochola\Psr\SimpleCache\ApcuSimpleCache;
use TomasChochola\Psr\SimpleCache\SimpleCaches;

use function apcu_enabled;
use function iterator_to_array;
use function opcache_is_script_cached;

use const PHP_SAPI;

/**
 * @no-named-arguments
 */
readonly class ContainerManifestCache
{
    /**
     * @return array<mixed, mixed>
     */
    public static function remember(ContainerManifest $manifest): array
    {
        if (!static::enabled()) {
            return iterator_to_array($manifest);
        }

        return SimpleCaches::remember(new ApcuSimpleCache(), $manifest::class, static fn(): array => iterator_to_array($manifest));
    }

    protected static function enabled(): bool
    {
        return apcu_enabled() && opcache_is_script_cached(__FILE__) && PHP_SAPI !== 'cli' && PHP_SAPI !== 'cli-server' && PHP_SAPI !== 'phpdbg';
    }
}
