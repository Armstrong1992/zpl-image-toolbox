<?php

declare(strict_types=1);

/*
 * This file is part of the "Zpl Image Toolbox" library.
 * (c) Paweł Smok (Armstrong) 2024
 *
 * For the full copyright and license information, please view the LICENSE file.
 */

namespace Armstrong1992\ZplImageToolBox\ImageData\Finder;

use Armstrong1992\ZplImageToolBox\ImageData\Collection\EncodedImageDataCollection;
use Armstrong1992\ZplImageToolBox\ImageData\EncodedImageData;
use Armstrong1992\ZplImageToolBox\ImageData\Finder\Exception\ImageDataFinderException;
use Armstrong1992\ZplImageToolBox\Utils\StringUtils;
use Armstrong1992\ZplImageToolBox\Utils\ZplUtils;

final class GfaFinder implements ImageDataFinderInterface
{
    public function findInData(mixed $data): EncodedImageDataCollection
    {
        if (!$this->supports($data)) {
            throw new ImageDataFinderException('Passed data is not supported by this finder.');
        }

        $encodedImageData = new EncodedImageDataCollection();

        $data = StringUtils::stripWhiteChars($data);

        foreach ($this->findStartingPositionOfImageData($data) as $imageDataStartPosition) {

            $imageDataChunk = trim(substr($data, $imageDataStartPosition));

            $imageDataParameters = $this->getImageDataParameters($imageDataChunk);

            if ($imageDataParameters === null) {
                continue;
            }

            $imageData = substr($imageDataChunk, $imageDataParameters['data_start_offset'], $imageDataParameters['data_length']);

            $encodedImageData->add(
                new EncodedImageData(
                    ZplUtils::ZPL_GFA_TAG,
                    $imageData,
                    $imageDataParameters['image_data_length'],
                    $imageDataParameters['image_row_length']
                )
            );
        }

        return $encodedImageData;
    }

    public function supports(mixed $data): bool
    {
        return is_string($data);
    }

    /**
     * @return int[]
     */
    private function findStartingPositionOfImageData(string $data): array
    {
        $matchedImageData = [];

        preg_match_all($this->getStartingPositionRegex(), $data, $matchedImageData, PREG_OFFSET_CAPTURE);

        if (\count($matchedImageData) > 0 && \count($matchedImageData[0]) > 0) {
            array_walk($matchedImageData[0], static function (&$matchedData) {
                $matchedData = $matchedData[1];
            });

            return $matchedImageData[0];
        }

        return [];
    }

    private function getStartingPositionRegex(): string
    {
        return '/'.$this->getEscapedZplTag().'/si';
    }

    /**
     * @return array{
     *      data_start_offset: int,
     *      data_length: int,
     *      image_data_length: int,
     *      image_row_length: int
     *  }|null
     */
    private function getImageDataParameters(string $imageDataChunk): ?array
    {
        $parameters = [];

        $isMatched = preg_match($this->getImageDataParametersRegex(), $imageDataChunk, $parameters) === 1;

        if (!$isMatched) {
            return null;
        }

        $dataLength      = (int) $parameters[1];
        $imageDataLength = (int) $parameters[2];
        $imageRowLength  = (int) $parameters[3];

        if ($dataLength === 0 || $imageDataLength === 0 || $imageRowLength === 0) {
            return null;
        }

        return [
            'data_start_offset' => strlen($parameters[0]),
            'data_length'       => $dataLength,
            'image_data_length' => $imageDataLength,
            'image_row_length'  => $imageRowLength,
        ];
    }

    private function getImageDataParametersRegex(): string
    {
        return '/^'.$this->getEscapedZplTag().',([0-9]+),([0-9]+),([0-9]+),/si';
    }

    private function getEscapedZplTag(): string
    {
        return preg_quote(ZplUtils::ZPL_GFA_TAG, '/');
    }
}
