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

use UnexpectedValueException;

use function apcu_enabled;
use function apcu_fetch;
use function apcu_store;
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
     * @param callable(): iterable<int|string, mixed> $fresh
     *
     * @return array<int|string, mixed>
     */
    public static function remember(callable $fresh): array
    {
        if (!static::enabled()) {
            return iterator_to_array($fresh());
        }

        $ok = false;
        $cache = apcu_fetch(static::class, $ok);

        if ($ok && is_array($cache)) {
            return $cache;
        }

        $config = iterator_to_array($fresh());
        $ok = apcu_store(static::class, $config);

        if (!$ok) {
            throw new UnexpectedValueException('apcu_store');
        }

        return $config;
    }

    protected static function enabled(): bool
    {
        return apcu_enabled() && opcache_is_script_cached(__FILE__) && PHP_SAPI !== 'cli' && PHP_SAPI !== 'cli-server' && PHP_SAPI !== 'phpdbg';
    }
}
