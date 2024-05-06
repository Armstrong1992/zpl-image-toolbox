<?php

declare(strict_types=1);

/*
 * This file is part of the "Zpl Image Toolbox" library.
 * (c) Paweł Smok (Armstrong) 2024
 *
 * For the full copyright and license information, please view the LICENSE file.
 */

namespace Armstrong1992\ZplImageToolBox\Validator;

use Armstrong1992\ZplImageToolBox\Validator\Exception\ValidatorException;

final readonly class ImageDataValidator
{
    public function __construct(
        private string $imageData,
        private int    $imageDataSize
    )
    {

    }

    /**
     * @throws ValidatorException
     */
    public function validate(): void
    {
        if (!ctype_xdigit($this->imageData)) {
            throw new ValidatorException('Image data must be in form of a HEX string.');
        }

        if (\strlen($this->imageData) / 2 !== $this->imageDataSize) {
            throw new ValidatorException('Image data size divided by 2 must be equal to imageDataSize parameter.');
        }
    }
}
