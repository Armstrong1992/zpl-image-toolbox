<?php

declare(strict_types=1);

/*
 * This file is part of the "Zpl Image Toolbox" library.
 * (c) Paweł Smok (Armstrong) 2024
 *
 * For the full copyright and license information, please view the LICENSE file.
 */

namespace Armstrong1992\ZplImageToolBox\Tests\Unit\ImageData\Decoder;

use Armstrong1992\ZplImageToolBox\ImageData\Decoder\Exception\DecoderException;
use Armstrong1992\ZplImageToolBox\ImageData\Decoder\GfaDecoder;
use Armstrong1992\ZplImageToolBox\ImageData\EncodedImageDataInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

#[CoversClass(GfaDecoder::class)]
final class GfaDecoderTest extends TestCase
{
    public function testSupports(): void
    {
        $gfaDecoder = $this->getGfaDecoder();

        $this->assertTrue($gfaDecoder->supports($this->mockEncodedImageData('^GFA')));
        $this->assertFalse($gfaDecoder->supports($this->mockEncodedImageData('^GFB')));
        $this->assertFalse($gfaDecoder->supports($this->mockEncodedImageData('^')));
        $this->assertFalse($gfaDecoder->supports($this->mockEncodedImageData('1234')));
    }

    public function testPassedNotSupportedData(): void
    {
        $this->expectException(DecoderException::class);
        $this->expectExceptionMessage('Passed encoded image data is not supported by this decoder.');

        $this->getGfaDecoder()->decode($this->mockEncodedImageData(''));
    }

    private function mockEncodedImageData(string $zplTagCode = '^GFA'): EncodedImageDataInterface&MockObject
    {
        $encodedImageData = $this->createMock(EncodedImageDataInterface::class);
        $encodedImageData->method('zplCodeTag')->willReturn($zplTagCode);

        return $encodedImageData;
    }

    private function getGfaDecoder(): GfaDecoder
    {
        return new GfaDecoder();
    }
}
