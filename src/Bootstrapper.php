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

use GlobIterator;
use TomasChochola\Psr\Http\RequestHandlers\ContainerRegistry as TomasChocholaPsrHttpRequestHandlersContainerRegistry;
use UnexpectedValueException;

use function getenv;
use function is_string;

/**
 * @no-named-arguments
 */
readonly class Bootstrapper
{
    /**
     * @var list<string>
     */
    public const ENVIRONMENT = [
        'APP_ENV',
        'APP_DEBUG',
    ];

    /**
     * @return iterable<int|string, mixed>
     */
    public static function load(): iterable
    {
        yield from static::environment();

        yield from static::ini();

        yield from static::env();

        yield from static::quickmux();
    }

    protected static function appenv(): string
    {
        $env = getenv('APP_ENV');

        if (!is_string($env)) {
            throw new UnexpectedValueException('APP_ENV');
        }

        return $env;
    }

    protected static function directory(): string
    {
        return '.';
    }

    /**
     * @return iterable<int|string, mixed>
     */
    protected static function env(): iterable
    {
        $scope = static::appenv();

        yield from new IniConfigLoader(new GlobIterator(static::directory() . '/config/' . $scope . '.ini'));

        yield from new IniConfigLoader(new GlobIterator(static::directory() . '/.env.' . $scope . '.ini'));
    }

    /**
     * @return iterable<int|string, mixed>
     */
    protected static function environment(): iterable
    {
        yield from new EnvConfigLoader(static::ENVIRONMENT);
    }

    /**
     * @return iterable<int|string, mixed>
     */
    protected static function ini(): iterable
    {
        yield from new IniConfigLoader(new GlobIterator(static::directory() . '/config/base.ini'));

        yield from new IniConfigLoader(new GlobIterator(static::directory() . '/.env.ini'));
    }

    /**
     * @return iterable<int|string, mixed>
     */
    protected static function quickmux(): iterable
    {
        yield from new TomasChocholaPsrHttpRequestHandlersContainerRegistry();

        yield from new ContainerRegistry();
    }
}
