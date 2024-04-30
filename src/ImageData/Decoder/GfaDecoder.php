<?php

declare(strict_types=1);

/*
 * This file is part of the "Zpl Image Toolbox" library.
 * (c) Paweł Smok (Armstrong) 2024
 *
 * For the full copyright and license information, please view the LICENSE file.
 */

namespace Armstrong1992\ZplImageToolBox\ImageData\Decoder;

use Armstrong1992\ZplImageToolBox\ImageData\Compressor\DecompressorInterface;
use Armstrong1992\ZplImageToolBox\ImageData\Compressor\Z64Compressor;
use Armstrong1992\ZplImageToolBox\ImageData\DecodedImageDataInterface;
use Armstrong1992\ZplImageToolBox\ImageData\Decoder\Exception\DecoderException;
use Armstrong1992\ZplImageToolBox\ImageData\EncodedImageDataInterface;
use Armstrong1992\ZplImageToolBox\Utils\ZplUtils;

final class GfaDecoder implements DecoderInterface
{
    private DecompressorInterface $z64Decompressor;
    public function __construct()
    {
        $this->z64Decompressor = new Z64Compressor();
    }
    public function supports(EncodedImageDataInterface $encodedImageData): bool
    {
        return strtoupper($encodedImageData->zplCodeTag()) === ZplUtils::ZPL_GFA_TAG;
    }

    public function decode(EncodedImageDataInterface $encodedImageData): DecodedImageDataInterface
    {
        if (!$this->supports($encodedImageData)) {
            throw new DecoderException('Passed encoded image data is not supported by this decoder.');
        }
    }
}
