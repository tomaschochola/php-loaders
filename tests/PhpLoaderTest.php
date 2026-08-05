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

use DirectoryIterator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Medium;
use PHPUnit\Framework\Attributes\Test;
use TomasChochola\Loaders\PhpLoader;
use UnexpectedValueException;

use function bin2hex;
use function file_put_contents;
use function iterator_to_array;
use function mkdir;
use function random_bytes;
use function rmdir;
use function sys_get_temp_dir;
use function unlink;

/**
 * @internal
 *
 * @no-named-arguments
 */
#[CoversClass(PhpLoader::class)]
#[Medium()]
final class PhpLoaderTest extends TestCase
{
    #[Test()]
    public function loadsFilesAndSkipsDirectories(): void
    {
        $directory = sys_get_temp_dir() . '/php-loaders-' . bin2hex(random_bytes(8));
        $nestedDirectory = "{$directory}/nested";

        self::assertTrue(mkdir($directory));
        self::assertTrue(mkdir($nestedDirectory));

        try {
            self::assertNotFalse(file_put_contents("{$directory}/first.php", "<?php return ['first' => 'one'];\n"));
            self::assertNotFalse(file_put_contents("{$directory}/second.php", "<?php return ['second' => 'two'];\n"));

            $loaded = iterator_to_array(new PhpLoader(new DirectoryIterator($directory)));

            self::assertCount(2, $loaded);
            self::assertArrayHasKey('first', $loaded);
            self::assertArrayHasKey('second', $loaded);
            self::assertSame('one', $loaded['first']);
            self::assertSame('two', $loaded['second']);
        } finally {
            self::assertTrue(unlink("{$directory}/first.php"));
            self::assertTrue(unlink("{$directory}/second.php"));
            self::assertTrue(rmdir($nestedDirectory));
            self::assertTrue(rmdir($directory));
        }
    }

    #[Test()]
    public function rejectsNonIterableResults(): void
    {
        $directory = sys_get_temp_dir() . '/php-loaders-' . bin2hex(random_bytes(8));

        self::assertTrue(mkdir($directory));

        try {
            self::assertNotFalse(file_put_contents("{$directory}/invalid.php", "<?php return 1;\n"));

            $this->expectExceptionObject(new UnexpectedValueException('require'));

            iterator_to_array(new PhpLoader(new DirectoryIterator($directory)));
        } finally {
            self::assertTrue(unlink("{$directory}/invalid.php"));
            self::assertTrue(rmdir($directory));
        }
    }
}
