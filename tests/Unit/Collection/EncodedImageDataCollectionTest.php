<?php

declare(strict_types=1);

/*
 * This file is part of the "Zpl Image Toolbox" library.
 * (c) Paweł Smok (Armstrong) 2024
 *
 * For the full copyright and license information, please view the LICENSE file.
 */

namespace Armstrong1992\ZplImageToolBox\Tests\Unit\Collection;

use Armstrong1992\ZplImageToolBox\ImageData\Collection\EncodedImageDataCollection;
use Armstrong1992\ZplImageToolBox\ImageData\EncodedImageDataInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(EncodedImageDataCollection::class)]
final class EncodedImageDataCollectionTest extends TestCase
{
    public function testCollection(): void
    {
        $collection = new EncodedImageDataCollection();

        $this->assertCount(0, $collection);
        $this->assertEquals(0, $collection->count());

        $collection->add($this->createMock(EncodedImageDataInterface::class));

        $this->assertCount(1, $collection);
        $this->assertEquals(1, $collection->count());
    }
}
