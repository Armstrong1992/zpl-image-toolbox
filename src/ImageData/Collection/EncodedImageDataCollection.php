<?php

declare(strict_types=1);

/*
 * This file is part of the "Zpl Image Toolbox" library.
 * (c) Paweł Smok (Armstrong) 2024
 *
 * For the full copyright and license information, please view the LICENSE file.
 */

namespace Armstrong1992\ZplImageToolBox\ImageData\Collection;

use Armstrong1992\ZplImageToolBox\ImageData\EncodedImageDataInterface;
use function count;

final class EncodedImageDataCollection implements \Countable
{
    /**
     * @var EncodedImageDataInterface[]
     */
    private array $data = [];

    public function count(): int
    {
        return count($this->data);
    }

    public function add(EncodedImageDataInterface $encodedImageData): void
    {
        $this->data[] = $encodedImageData;
    }
}
