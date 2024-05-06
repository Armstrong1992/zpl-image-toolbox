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
use Armstrong1992\ZplImageToolBox\ImageData\Compressor\Exception\CompressorException;
use Armstrong1992\ZplImageToolBox\ImageData\DecodedImageData;
use Armstrong1992\ZplImageToolBox\ImageData\DecodedImageDataInterface;
use Armstrong1992\ZplImageToolBox\ImageData\Decoder\Exception\DecoderException;
use Armstrong1992\ZplImageToolBox\ImageData\EncodedImageDataInterface;
use Armstrong1992\ZplImageToolBox\Utils\ZplUtils;
use Armstrong1992\ZplImageToolBox\Validator\Exception\ValidatorException;

final class GfaDecoder implements DecoderInterface
{
    /**
     * @var DecompressorInterface[]
     */
    private array $decompressors = [];

    /**
     * @param DecompressorInterface[] $decompressors
     */
    public function __construct(array $decompressors)
    {
        foreach ($decompressors as $decompressor) {
            $this->addDecompressor($decompressor);
        }
    }

    private function addDecompressor(DecompressorInterface $decompressor): void
    {
        $this->decompressors[] = $decompressor;
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

        $decompressedImageData = null;

        $decompressExceptions = [];

        foreach ($this->decompressors as $decompressor) {
            $compressedImageData = $encodedImageData->data();
            if ($decompressor->supports($compressedImageData)) {
                try {
                    $decompressedImageData = $decompressor->decompress(
                        $compressedImageData,
                        $encodedImageData->imageDataSize(),
                        $encodedImageData->imageDataRowSize()
                    );
                    break;
                } catch (CompressorException $exception) {
                    $decompressExceptions[] = sprintf('%s: "%s"', $decompressor::class, $exception->getMessage());
                }
            }
        }

        if ($decompressedImageData === null) {
            $exceptionMsg = 'None of the decompressors was able to decompress provided encoded image data.';
            if (\count($decompressExceptions) > 0) {
                $exceptionMsg .= ' ';
                $exceptionMsg .= sprintf('Additional info returned by decompressors: (%s)', implode(',', $decompressExceptions));
            }
            throw new DecoderException($exceptionMsg);
        }

        try {
            $decodedImageData = new DecodedImageData(
                $decompressedImageData,
                $encodedImageData->imageDataSize(),
                $encodedImageData->imageDataRowSize()
            );
        } catch (ValidatorException $exception) {
            throw new DecoderException(
                sprintf('Cannot create decoded image data: "%s"', $exception->getMessage())
            );
        }

        return $decodedImageData;
    }
}
