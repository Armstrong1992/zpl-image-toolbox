<?php

declare(strict_types=1);

/*
 * This file is part of the "Zpl Image Toolbox" library.
 * (c) Paweł Smok (Armstrong) 2024
 *
 * For the full copyright and license information, please view the LICENSE file.
 */

namespace Armstrong1992\ZplImageToolBox\ImageData\Decoder;

use Armstrong1992\ZplImageToolBox\ImageData\DecodedImageDataInterface;
use Armstrong1992\ZplImageToolBox\ImageData\Decoder\Exception\DecoderException;
use Armstrong1992\ZplImageToolBox\ImageData\EncodedImageDataInterface;

interface DecoderInterface
{
    /**
     * @throws DecoderException
     */
    public function decode(EncodedImageDataInterface $encodedImageData): DecodedImageDataInterface;

    public function supports(EncodedImageDataInterface $encodedImageData): bool;
}
