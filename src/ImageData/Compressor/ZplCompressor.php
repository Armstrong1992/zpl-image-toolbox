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
use function strlen;

final class ZplCompressor implements DecompressorInterface
{
    private const array HEX_CHARS = [
        '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'A', 'B', 'C', 'D', 'E', 'F', 'a', 'b', 'c', 'd', 'e', 'f',
    ];

    private const array ZPL_REPEAT_COUNT_CHAR_MAP = [
        'G' => 1,
        'H' => 2,
        'I' => 3,
        'J' => 4,
        'K' => 5,
        'L' => 6,
        'M' => 7,
        'N' => 8,
        'O' => 9,
        'P' => 10,
        'Q' => 11,
        'R' => 12,
        'S' => 13,
        'T' => 14,
        'U' => 15,
        'V' => 16,
        'W' => 17,
        'X' => 18,
        'Y' => 19,
        'g' => 20,
        'h' => 40,
        'i' => 60,
        'j' => 80,
        'k' => 100,
        'l' => 120,
        'm' => 140,
        'n' => 160,
        'o' => 180,
        'p' => 200,
        'q' => 220,
        'r' => 240,
        's' => 260,
        't' => 280,
        'u' => 300,
        'v' => 320,
        'w' => 340,
        'x' => 360,
        'y' => 380,
        'z' => 400,
    ];

    private const array ZPL_REPEAT_VALUE_CHAR_MAP = [
        ',', '!', ':',
    ];


    public function decompress(string $compressedData, int $imageDataSize, int $imageDataRowSize): string
    {
        if (!$this->supports($compressedData)) {
            throw new CompressorException('Provided compressed data is not supported by this decompressor.');
        }

        $charsPerLine         = $imageDataRowSize * 2;
        $compressedDataLength = strlen($compressedData);

        $decompressedData = '';
        for ($index = 0; $index < $compressedDataLength; $index++) {
            $char = $compressedData[$index];

            if (array_key_exists($char, self::ZPL_REPEAT_COUNT_CHAR_MAP)) {
                $multiplierCode = '';
                while (array_key_exists($char, self::ZPL_REPEAT_COUNT_CHAR_MAP)) {
                    $multiplierCode .= $char;
                    $char           = $compressedData[++$index];
                }
                $multiplier           = 0;
                $multiplierCodeLength = strlen($multiplierCode);

                for ($i = 0; $i < $multiplierCodeLength; $i++) {
                    $multiplier += self::ZPL_REPEAT_COUNT_CHAR_MAP[$multiplierCode[$i]];
                }

                $decompressedData .= str_repeat($char, $multiplier);

            } elseif (in_array($char, self::ZPL_REPEAT_VALUE_CHAR_MAP)) {
                $currentDataSize = strlen($decompressedData);
                $remainLength    = $charsPerLine - ($currentDataSize % $charsPerLine);

                $decompressedData .= match ($char) {
                    ',' => str_repeat('0', $remainLength),
                    '!' => str_repeat('F', $remainLength),
                    ':' => substr($decompressedData, $currentDataSize - $charsPerLine, $currentDataSize)
                };
            } else {
                $decompressedData .= $char;
            }
        }

        $decompressedDataSize = strlen($decompressedData) / 2;

        if ($decompressedDataSize !== $imageDataSize) {
            throw new CompressorException(
                sprintf(
                    'Decompressed data size (%d) does not match expected size (%d).',
                    $decompressedDataSize,
                    $imageDataSize
                )
            );
        }

        return $decompressedData;
    }

    public function supports(string $compressedData): bool
    {
        $isEmpty = $compressedData === '';

        $notMatches = preg_match(
                sprintf(
                    '/[^%s]/',
                    implode(
                        '',
                        array_merge(
                            self::HEX_CHARS,
                            array_keys(self::ZPL_REPEAT_COUNT_CHAR_MAP),
                            self::ZPL_REPEAT_VALUE_CHAR_MAP
                        )
                    )
                ),
                $compressedData
            ) === 1;

        return !$isEmpty && !$notMatches;
    }
}
