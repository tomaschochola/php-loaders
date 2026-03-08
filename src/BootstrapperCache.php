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
use UnexpectedValueException;

use function apcu_enabled;
use function is_array;
use function iterator_to_array;
use function opcache_is_script_cached;

use const PHP_SAPI;

/**
 * @no-named-arguments
 */
readonly class BootstrapperCache
{
    /**
     * @param callable(): iterable<mixed, mixed> $fresh
     *
     * @return array<mixed, mixed>
     */
    public static function remember(callable $fresh): array
    {
        if (!static::enabled()) {
            return iterator_to_array($fresh());
        }

        $cache = new ApcuSimpleCache();
        $config = $cache->get(static::class);

        if (is_array($config)) {
            return $config;
        }

        $config = iterator_to_array($fresh());
        $ok = $cache->set(static::class, $config);

        if (!$ok) {
            throw new UnexpectedValueException($cache::class . '->set');
        }

        return $config;
    }

    protected static function enabled(): bool
    {
        return apcu_enabled() && opcache_is_script_cached(__FILE__) && PHP_SAPI !== 'cli' && PHP_SAPI !== 'cli-server' && PHP_SAPI !== 'phpdbg';
    }
}
