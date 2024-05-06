<?php

declare(strict_types=1);

/*
 * This file is part of the "Zpl Image Toolbox" library.
 * (c) Paweł Smok (Armstrong) 2024
 *
 * For the full copyright and license information, please view the LICENSE file.
 */

namespace Armstrong1992\ZplImageToolBox\Tests\Unit\ImageData;

use Armstrong1992\ZplImageToolBox\ImageData\EncodedImageData;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(EncodedImageData::class)]
final class EncodedImageDataTest extends TestCase
{
    public function testCreation(): void
    {
        $encodedImageData = new EncodedImageData('^GFZ', 'asd', 12, 99);

        $this->assertEquals('^GFZ', $encodedImageData->zplCodeTag());
        $this->assertEquals('asd', $encodedImageData->data());
        $this->assertEquals(12, $encodedImageData->imageDataSize());
        $this->assertEquals(99, $encodedImageData->imageDataRowSize());
    }
}
