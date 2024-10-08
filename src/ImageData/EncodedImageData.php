<?php

declare(strict_types=1);

/*
 * This file is part of the "Zpl Image Toolbox" library.
 * (c) Paweł Smok (Armstrong) 2024
 *
 * For the full copyright and license information, please view the LICENSE file.
 */

namespace Armstrong1992\ZplImageToolBox\ImageData;

final class EncodedImageData implements EncodedImageDataInterface
{
    public function __construct(
        private string $zplCodeTag,
        private string $data,
        private int    $imageDataSize,
        private int    $imageDataRowSize
    )
    {

    }

    public function zplCodeTag(): string
    {
        return $this->zplCodeTag;
    }

    public function data(): string
    {
        return $this->data;
    }

    public function imageDataSize(): int
    {
        return $this->imageDataSize;
    }

    public function imageDataRowSize(): int
    {
        return $this->imageDataRowSize;
    }
}
