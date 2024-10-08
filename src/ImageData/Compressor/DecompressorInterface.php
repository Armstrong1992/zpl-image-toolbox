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

interface DecompressorInterface
{
    /**
     * @throws CompressorException
     */
    public function decompress(string $compressedData, int $imageDataSize, int $imageDataRowSize): string;

    public function supports(string $compressedData): bool;
}
