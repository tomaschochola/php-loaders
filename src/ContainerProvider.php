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
use IteratorAggregate;
use Override;
use TomasChochola\Psr\Clock\ClockProvider;
use TomasChochola\Psr\Clock\ClockOverrideProvider;
use TomasChochola\Psr\Http\Client\HttpClientProvider;
use TomasChochola\Psr\Http\Factory\HttpFactoryProvider;
use TomasChochola\Psr\Http\RequestHandlers\RequestHandlersOverrideProvider;
use TomasChochola\Psr\Http\RequestHandlers\RequestHandlersProvider;
use TomasChochola\Psr\Log\LoggerProvider;
use TomasChochola\Psr\SimpleCache\SimpleCacheOverrideProvider;
use TomasChochola\Psr\SimpleCache\SimpleCacheProvider;
use Traversable;
use UnexpectedValueException;

use function getenv;
use function is_string;

/**
 * @no-named-arguments
 *
 * @implements IteratorAggregate<mixed, mixed>
 */
readonly class ContainerProvider implements IteratorAggregate
{
    /**
     * @var list<string>
     */
    public const ENVIRONMENT = [
        'APP_ENV',
    ];

    #[Override]
    public function getIterator(): Traversable
    {
        yield from static::environment();
        yield from static::ini();
        yield from static::php();
        yield from static::framework();

        if (static::testsuite()) {
            yield from static::phpunit();
            yield from static::overrides();
        }
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

    protected static function testsuite(): bool
    {
        $phpunit = getenv('PHPUNIT_TESTSUITE');

        return is_string($phpunit) && $phpunit !== '';
    }

    /**
     * @return iterable<mixed, mixed>
     */
    protected static function environment(): iterable
    {
        yield from new EnvLoader(static::ENVIRONMENT);
    }

    /**
     * @return iterable<mixed, mixed>
     */
    protected static function ini(): iterable
    {
        $scope = static::appenv();

        yield from new IniLoader(new GlobIterator(static::directory() . '/config/base.ini'));
        yield from new IniLoader(new GlobIterator(static::directory() . '/config/' . $scope . '.ini'));

        yield from new IniLoader(new GlobIterator(static::directory() . '/.env.ini'));
        yield from new IniLoader(new GlobIterator(static::directory() . '/.env.' . $scope . '.ini'));
    }

    /**
     * @return iterable<mixed, mixed>
     */
    protected static function php(): iterable
    {
        $scope = static::appenv();

        yield from new PhpLoader(new GlobIterator(static::directory() . '/config/base.php'));
        yield from new PhpLoader(new GlobIterator(static::directory() . '/config/' . $scope . '.php'));

        yield from new PhpLoader(new GlobIterator(static::directory() . '/.env.php'));
        yield from new PhpLoader(new GlobIterator(static::directory() . '/.env.' . $scope . '.php'));
    }

    /**
     * @return iterable<mixed, mixed>
     */
    protected static function framework(): iterable
    {
        yield from new ClockProvider();
        yield from new HttpClientProvider();
        yield from new HttpFactoryProvider();
        yield from new RequestHandlersProvider();
        yield from new LoggerProvider();
        yield from new SimpleCacheProvider();
    }

    /**
     * @return iterable<mixed, mixed>
     */
    protected static function overrides(): iterable
    {
        yield from new ClockOverrideProvider();
        yield from new RequestHandlersOverrideProvider();
        yield from new SimpleCacheOverrideProvider();
    }

    /**
     * @return iterable<mixed, mixed>
     */
    protected static function phpunit(): iterable
    {
        yield from new IniLoader(new GlobIterator(static::directory() . '/config/phpunit.ini'));
        yield from new IniLoader(new GlobIterator(static::directory() . '/.phpunit.ini'));

        yield from new PhpLoader(new GlobIterator(static::directory() . '/config/phpunit.php'));
        yield from new PhpLoader(new GlobIterator(static::directory() . '/.phpunit.php'));
    }
}
