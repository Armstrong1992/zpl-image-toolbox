<?php

declare(strict_types=1);

/*
 * This file is part of the "Zpl Image Toolbox" library.
 * (c) Paweł Smok (Armstrong) 2024
 *
 * For the full copyright and license information, please view the LICENSE file.
 */

namespace Armstrong1992\ZplImageToolBox\ImageData\Compressor;

use Armstrong1992\ZplImageToolBox\ImageData\Compressor\Exception\CompressorException;

final class Z64Compressor implements DecompressorInterface
{
    private const string DATA_PREFIX      = ':Z64:';
    private const int    ZLIB_DATA_OFFSET = 2;

    public function decompress(string $compressedData, int $imageDataSize, int $imageDataRowSize): string
    {
        if (!$this->supports($compressedData)) {
            throw new CompressorException('Provided compressed data is not supported by this decompressor.');
        }

        $compressedData      = substr($compressedData, strlen(self::DATA_PREFIX));
        $endOfCompressedData = strpos($compressedData, ':');
        $compressedData      = substr($compressedData, 0, $endOfCompressedData !== false ? $endOfCompressedData : \strlen($compressedData));

        if (!$this->isBase64Encoded($compressedData)) {
            throw new CompressorException('Provided compressed data does not contain base64 encoded data.');
        }

        $zlibData = base64_decode($compressedData);

        $zlibContext = inflate_init(ZLIB_ENCODING_RAW);

        $uncompressed = inflate_add($zlibContext, substr($zlibData, self::ZLIB_DATA_OFFSET));

        if ($uncompressed === false || $uncompressed === '') {
            throw new CompressorException('Unable to decompress ZLIB data.');
        }

        return bin2hex($uncompressed);
    }

    public function supports(string $compressedData): bool
    {
        return stripos($compressedData, self::DATA_PREFIX) === 0;
    }

    private function isBase64Encoded(string $data): bool
    {
        $decoded = base64_decode($data, true);
        if ($decoded === false) {
            return false;
        }

        return base64_encode($decoded) === $data;
    }
}
