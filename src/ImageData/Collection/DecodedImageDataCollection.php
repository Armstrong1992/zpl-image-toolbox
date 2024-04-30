<?php

declare(strict_types=1);

/*
 * This file is part of the "Zpl Image Toolbox" library.
 * (c) Paweł Smok (Armstrong) 2024
 *
 * For the full copyright and license information, please view the LICENSE file.
 */

namespace Armstrong1992\ZplImageToolBox\ImageData\Collection;

use Armstrong1992\ZplImageToolBox\ImageData\DecodedImageDataInterface;

final class DecodedImageDataCollection implements \Countable
{
    /**
     * @var DecodedImageDataInterface[]
     */
    private array $data = [];

    public function count(): int
    {
        return \count($this->data);
    }

    public function add(DecodedImageDataInterface $encodedImageData): void
    {
        $this->data[] = $encodedImageData;
    }
}
