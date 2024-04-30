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
use Armstrong1992\ZplImageToolBox\ImageData\Finder\Exception\ImageDataFinderException;

interface ImageDataFinderInterface
{
    /**
     * @throws ImageDataFinderException
     */
    public function findInData(mixed $data): EncodedImageDataCollection;

    public function supports(mixed $data): bool;
}
