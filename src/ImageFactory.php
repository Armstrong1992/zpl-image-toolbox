<?php

declare(strict_types=1);

/*
 * This file is part of the "Zpl Image Toolbox" library.
 * (c) Paweł Smok (Armstrong) 2024
 *
 * For the full copyright and license information, please view the LICENSE file.
 */

namespace Armstrong1992\ZplImageToolBox;

use Armstrong1992\ZplImageToolBox\Exception\ImageFactoryException;
use Armstrong1992\ZplImageToolBox\ImageData\DecodedImageDataInterface;
use Armstrong1992\ZplImageToolBox\Validator\Exception\ValidatorException;
use Armstrong1992\ZplImageToolBox\Validator\ImageDataValidator;
use GdImage;

final class ImageFactory implements ImageFactoryInterface
{
    public function create(DecodedImageDataInterface $decodedImageData): GdImage
    {
        $imageData        = $decodedImageData->data();
        $imageDataSize    = $decodedImageData->imageDataSize();
        $imageDataRowSize = $decodedImageData->imageDataRowSize() * 8;

        $validator = new ImageDataValidator($imageData, $imageDataSize);

        try {
            $validator->validate();
        } catch (ValidatorException $exception) {
            throw new ImageFactoryException(
                sprintf('Provided image data is not valid. (%s)', $exception->getMessage())
            );
        }

        $binaryImageData     = $this->convertToBinary($imageData);
        $binaryImageDataSize = strlen($binaryImageData);

        $img = $this->createPlainImage($imageDataRowSize, $imageDataSize / ($imageDataRowSize / 8));

        $fillColor       = $this->getFillColor($img);
        $backgroundColor = $this->getBackgroundColor($img);

        $currentX = 0;
        $currentY = 0;

        for ($i = 0; $i < $binaryImageDataSize; $i++) {
            if ($currentX === $imageDataRowSize) {
                $currentX = 0;
                $currentY++;
            }
            imagesetpixel($img, $currentX, $currentY, $binaryImageData[$i] !== "0" ? $fillColor : $backgroundColor);
            $currentX++;
        }

        return $img;

    }

    private function createPlainImage(int $width, int $height): GdImage
    {
        $img = imagecreate($width, $height);

        imagefill($img, 0, 0, $this->getBackgroundColor($img));

        return $img;
    }

    private function getBackgroundColor(GdImage $image): int
    {
        return imagecolorallocate($image, 255, 255, 255);
    }

    private function getFillColor(GdImage $image): int
    {
        return imagecolorallocate($image, 0, 0, 0);
    }

    private function convertToBinary(string $imageData): string
    {
        $imageDataSize = strlen($imageData);

        $binaryData = '';

        for ($i = 0; $i < $imageDataSize; $i += 2) {
            $binaryCode = base_convert($imageData[$i].$imageData[$i + 1], 16, 2);
            $binaryData .= str_pad($binaryCode, 8, "0", STR_PAD_LEFT);
        }

        return $binaryData;
    }
}
