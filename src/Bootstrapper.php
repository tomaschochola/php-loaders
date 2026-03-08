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
use TomasChochola\Psr\Clock\ClockManifest;
use TomasChochola\Psr\Clock\ClockOverrideManifest;
use TomasChochola\Psr\Http\Client\HttpClientManifest;
use TomasChochola\Psr\Http\Factory\HttpFactoryManifest;
use TomasChochola\Psr\Http\RequestHandlers\RequestHandlersManifest;
use TomasChochola\Psr\Http\RequestHandlers\RequestHandlersOverrideManifest;
use TomasChochola\Psr\Log\LoggerManifest;
use TomasChochola\Psr\SimpleCache\SimpleCacheManifest;
use TomasChochola\Psr\SimpleCache\SimpleCacheOverrideManifest;
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
    ];

    /**
     * @return iterable<mixed, mixed>
     */
    public static function bootstrap(): iterable
    {
        yield from static::environment();

        yield from static::ini();

        yield from static::php();

        if (static::phpunit()) {
            yield from static::override();
        }

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

    protected static function phpunit(): bool
    {
        $phpunit = getenv('PHPUNIT_TESTSUITE');

        return is_string($phpunit) && $phpunit !== '';
    }

    protected static function directory(): string
    {
        return '.';
    }

    /**
     * @return iterable<mixed, mixed>
     */
    protected static function environment(): iterable
    {
        yield from new EnvManifest(static::ENVIRONMENT);
    }

    /**
     * @return iterable<mixed, mixed>
     */
    protected static function ini(): iterable
    {
        $scope = static::appenv();

        yield from new IniManifest(new GlobIterator(static::directory() . '/config/base.ini'));
        yield from new IniManifest(new GlobIterator(static::directory() . '/config/' . $scope . '.ini'));

        yield from new IniManifest(new GlobIterator(static::directory() . '/.env.ini'));
        yield from new IniManifest(new GlobIterator(static::directory() . '/.env.' . $scope . '.ini'));

        if (static::phpunit()) {
            yield from new IniManifest(new GlobIterator(static::directory() . '/config/phpunit.ini'));
            yield from new IniManifest(new GlobIterator(static::directory() . '/.phpunit.ini'));
        }
    }

    /**
     * @return iterable<mixed, mixed>
     */
    protected static function php(): iterable
    {
        $scope = static::appenv();

        yield from new PhpManifest(new GlobIterator(static::directory() . '/config/base.php'));
        yield from new PhpManifest(new GlobIterator(static::directory() . '/config/' . $scope . '.php'));

        yield from new PhpManifest(new GlobIterator(static::directory() . '/.env.php'));
        yield from new PhpManifest(new GlobIterator(static::directory() . '/.env.' . $scope . '.php'));

        if (static::phpunit()) {
            yield from new PhpManifest(new GlobIterator(static::directory() . '/config/phpunit.php'));
            yield from new PhpManifest(new GlobIterator(static::directory() . '/.phpunit.php'));
        }
    }

    /**
     * @return iterable<mixed, mixed>
     */
    protected static function quickmux(): iterable
    {
        yield from new ClockManifest();
        yield from new HttpClientManifest();
        yield from new HttpFactoryManifest();
        yield from new RequestHandlersManifest();
        yield from new LoggerManifest();
        yield from new SimpleCacheManifest();
    }

    /**
     * @return iterable<mixed, mixed>
     */
    protected static function override(): iterable
    {
        yield from new ClockOverrideManifest();
        yield from new RequestHandlersOverrideManifest();
        yield from new SimpleCacheOverrideManifest();
    }
}
