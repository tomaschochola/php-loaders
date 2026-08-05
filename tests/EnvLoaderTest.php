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

namespace Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Small;
use PHPUnit\Framework\Attributes\Test;
use TomasChochola\Loaders\EnvLoader;
use UnexpectedValueException;

use function getenv;
use function iterator_to_array;
use function putenv;

/**
 * @internal
 *
 * @no-named-arguments
 */
#[CoversClass(EnvLoader::class)]
#[Small()]
final class EnvLoaderTest extends TestCase
{
    #[Test()]
    public function appliesMissingValuePolicy(): void
    {
        $key = 'PHP_LOADERS_MISSING';
        $original = getenv($key);

        try {
            self::assertTrue(putenv($key));
            self::assertSame([], iterator_to_array(new EnvLoader([$key])));
            self::assertSame([$key => ''], iterator_to_array(new EnvLoader([$key], true)));

            $this->expectExceptionObject(new UnexpectedValueException($key));

            iterator_to_array(new EnvLoader([$key], false));
        } finally {
            self::assertTrue(putenv($original === false ? $key : "{$key}={$original}"));
        }
    }

    #[Test()]
    public function mapsPresentValues(): void
    {
        $directKey = 'PHP_LOADERS_DIRECT';
        $mappedKey = 'PHP_LOADERS_MAPPED';
        $directOriginal = getenv($directKey);
        $mappedOriginal = getenv($mappedKey);

        try {
            self::assertTrue(putenv("{$directKey}=direct"));
            self::assertTrue(putenv("{$mappedKey}=mapped"));

            self::assertSame(
                [$directKey => 'direct', 'alias' => 'mapped'],
                iterator_to_array(new EnvLoader([$directKey, $mappedKey => 'alias'])),
            );
        } finally {
            self::assertTrue(putenv($directOriginal === false ? $directKey : "{$directKey}={$directOriginal}"));
            self::assertTrue(putenv($mappedOriginal === false ? $mappedKey : "{$mappedKey}={$mappedOriginal}"));
        }
    }
}
