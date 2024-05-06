<?php

declare(strict_types=1);

/*
 * This file is part of the "Zpl Image Toolbox" library.
 * (c) Paweł Smok (Armstrong) 2024
 *
 * For the full copyright and license information, please view the LICENSE file.
 */

namespace Armstrong1992\ZplImageToolBox\Tests\Unit\ImageData;

use Armstrong1992\ZplImageToolBox\ImageData\DecodedImageData;
use Armstrong1992\ZplImageToolBox\Validator\Exception\ValidatorException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(DecodedImageData::class)]
final class DecodedImageDataTest extends TestCase
{
    public function testInvalidDataPassed(): void
    {
        $this->expectException(ValidatorException::class);

        (new DecodedImageData('asd', 1, 1));
    }

    public function testCreation(): void
    {
        $decodedImageData = (new DecodedImageData('AF42', 2, 1));

        $this->assertEquals('AF42', $decodedImageData->data());
        $this->assertEquals(2, $decodedImageData->imageDataSize());
        $this->assertEquals(1, $decodedImageData->imageDataRowSize());
    }
}
