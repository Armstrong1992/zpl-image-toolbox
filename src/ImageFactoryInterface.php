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

interface ImageFactoryInterface
{
    /**
     * @throws ImageFactoryException
     */
    public function create(DecodedImageDataInterface $decodedImageData): \GdImage;
}
