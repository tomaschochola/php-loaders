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

use function filter_var;
use function getenv;
use function is_bool;

use const FILTER_NULL_ON_FAILURE;
use const FILTER_VALIDATE_BOOLEAN;

/**
 * @no-named-arguments
 */
readonly class TESTS_RUNNING
{
    public static function current(): bool
    {
        $env = getenv('TESTS_RUNNING');

        if ($env === false) {
            return false;
        }

        $env = filter_var($env, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);

        if (!is_bool($env)) {
            throw new UnexpectedValueException('TESTS_RUNNING');
        }

        return $env;
    }
}
