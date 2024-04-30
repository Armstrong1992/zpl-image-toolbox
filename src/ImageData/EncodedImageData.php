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
        private int    $imageDataLength,
        private int    $imageRowLength
    )
    {

    }

    public function zplCodeTag(): string
    {
        return $this->zplCodeTag;
    }
}
