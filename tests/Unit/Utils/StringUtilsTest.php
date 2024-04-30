<?php

declare(strict_types=1);

/*
 * This file is part of the "Zpl Image Toolbox" library.
 * (c) Paweł Smok (Armstrong) 2024
 *
 * For the full copyright and license information, please view the LICENSE file.
 */

namespace Armstrong1992\ZplImageToolBox\Tests\Unit\Utils;

use Armstrong1992\ZplImageToolBox\Utils\StringUtils;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(StringUtils::class)]
final class StringUtilsTest extends TestCase
{
    public function testStripWhiteChars(): void
    {
        $this->assertEquals('asd', StringUtils::stripWhiteChars("a\r\ns d"));
        $this->assertEquals(
            'asd',
            StringUtils::stripWhiteChars("  a\r
            \ns
             d  "
            )
        );
    }
}
