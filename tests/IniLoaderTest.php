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
use TomasChochola\Loaders\IniLoader;

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
#[CoversClass(IniLoader::class)]
#[Medium()]
final class IniLoaderTest extends TestCase
{
    #[Test()]
    public function loadsFilesAndSkipsDirectories(): void
    {
        $directory = sys_get_temp_dir() . '/php-loaders-' . bin2hex(random_bytes(8));
        $nestedDirectory = "{$directory}/nested";

        self::assertTrue(mkdir($directory));
        self::assertTrue(mkdir($nestedDirectory));

        try {
            self::assertNotFalse(file_put_contents("{$directory}/first.ini", "first = one\n"));
            self::assertNotFalse(file_put_contents("{$directory}/second.ini", "[section]\nsecond = two\n"));

            $loaded = iterator_to_array(new IniLoader(new DirectoryIterator($directory)));

            self::assertCount(2, $loaded);
            self::assertArrayHasKey('first', $loaded);
            self::assertArrayHasKey('section', $loaded);
            self::assertSame('one', $loaded['first']);
            self::assertSame(['second' => 'two'], $loaded['section']);
        } finally {
            self::assertTrue(unlink("{$directory}/first.ini"));
            self::assertTrue(unlink("{$directory}/second.ini"));
            self::assertTrue(rmdir($nestedDirectory));
            self::assertTrue(rmdir($directory));
        }
    }
}
