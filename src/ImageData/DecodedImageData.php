<?php

declare(strict_types=1);

/*
 * This file is part of the "Zpl Image Toolbox" library.
 * (c) Paweł Smok (Armstrong) 2024
 *
 * For the full copyright and license information, please view the LICENSE file.
 */

namespace Armstrong1992\ZplImageToolBox\ImageData;

use Armstrong1992\ZplImageToolBox\Validator\Exception\ValidatorException;
use Armstrong1992\ZplImageToolBox\Validator\ImageDataValidator;

final class DecodedImageData implements DecodedImageDataInterface
{
    /**
     * @throws ValidatorException
     */
    public function __construct(
        private string $data,
        private int    $imageDataSize,
        private int    $imageDataRowSize
    )
    {
        (new ImageDataValidator($this->data, $this->imageDataSize))->validate();
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
