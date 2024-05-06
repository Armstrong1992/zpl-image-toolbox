<?php

declare(strict_types=1);

/*
 * This file is part of the "Zpl Image Toolbox" library.
 * (c) Paweł Smok (Armstrong) 2024
 *
 * For the full copyright and license information, please view the LICENSE file.
 */

namespace Armstrong1992\ZplImageToolBox\Tests\Unit\ImageData\Decoder;

use Armstrong1992\ZplImageToolBox\ImageData\Compressor\DecompressorInterface;
use Armstrong1992\ZplImageToolBox\ImageData\Compressor\Exception\CompressorException;
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

    public function testDecodeWithNoDecompressors(): void
    {
        $gfaDecoder = $this->getGfaDecoder([]);

        $this->expectException(DecoderException::class);
        $this->expectExceptionMessage('None of the decompressors was able to decompress provided encoded image data.');

        $gfaDecoder->decode($this->mockEncodedImageData());

    }

    public function testDecodeWithNoSupportedDecompressors(): void
    {
        $aDecompressor = $this->createMock(DecompressorInterface::class);
        $aDecompressor->method('supports')->willReturn(false);

        $bDecompressor = $this->createMock(DecompressorInterface::class);
        $bDecompressor->method('supports')->willReturn(false);

        $gfaDecoder = $this->getGfaDecoder([$aDecompressor, $bDecompressor]);

        $this->expectException(DecoderException::class);
        $this->expectExceptionMessage('None of the decompressors was able to decompress provided encoded image data.');

        $gfaDecoder->decode($this->mockEncodedImageData());

    }

    public function testDecodeWithDecompressorThrowingException(): void
    {
        $aDecompressor = $this->createMock(DecompressorInterface::class);
        $aDecompressor->method('supports')->willReturn(true);
        $aDecompressor->method('decompress')->willThrowException(new CompressorException('aaaa'));

        $bDecompressor = $this->createMock(DecompressorInterface::class);
        $bDecompressor->method('supports')->willReturn(true);
        $bDecompressor->method('decompress')->willThrowException(new CompressorException('aaaa'));

        $gfaDecoder = $this->getGfaDecoder([$aDecompressor, $bDecompressor]);

        $this->expectException(DecoderException::class);
        $this->expectExceptionMessage('None of the decompressors was able to decompress provided encoded image data. Additional info returned by decompressors:');

        $gfaDecoder->decode($this->mockEncodedImageData());

    }

    public function testDecodedImageDataIsInvalid(): void
    {
        $decompressor = $this->createMock(DecompressorInterface::class);
        $decompressor->method('supports')->willReturn(true);
        $decompressor->method('decompress')->willReturn('asd123');

        $this->expectException(DecoderException::class);
        $this->expectExceptionMessage('Cannot create decoded image data: "Image data must be in form of a HEX string."');

        $gfaDecoder = $this->getGfaDecoder([$decompressor]);

        $gfaDecoder->decode($this->mockEncodedImageData(imageDataSize: 1));
    }

    public function testDecode(): void
    {
        $decompressor = $this->createMock(DecompressorInterface::class);
        $decompressor->method('supports')->willReturn(true);
        $decompressor->method('decompress')->willReturn('AF12');

        $gfaDecoder = $this->getGfaDecoder([$decompressor]);

        $decodedData = $gfaDecoder->decode($this->mockEncodedImageData(imageDataSize: 2, imageDataRowSize: 4));

        $this->assertEquals('AF12', $decodedData->data());

    }

    private function mockEncodedImageData(
        string $zplTagCode = '^GFA',
        string $imageData = '',
        int    $imageDataSize = 0,
        int    $imageDataRowSize = 0
    ): EncodedImageDataInterface&MockObject
    {
        $encodedImageData = $this->createMock(EncodedImageDataInterface::class);
        $encodedImageData->method('zplCodeTag')->willReturn($zplTagCode);
        $encodedImageData->method('data')->willReturn($imageData);
        $encodedImageData->method('imageDataSize')->willReturn($imageDataSize);
        $encodedImageData->method('imageDataRowSize')->willReturn($imageDataRowSize);

        return $encodedImageData;
    }

    private function getGfaDecoder(array $decompressors = []): GfaDecoder
    {
        return new GfaDecoder($decompressors);
    }
}
