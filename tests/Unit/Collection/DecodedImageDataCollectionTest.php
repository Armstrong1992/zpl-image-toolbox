<?php

declare(strict_types=1);

/*
 * This file is part of the "Zpl Image Toolbox" library.
 * (c) Paweł Smok (Armstrong) 2024
 *
 * For the full copyright and license information, please view the LICENSE file.
 */

namespace Armstrong1992\ZplImageToolBox\Tests\Unit\Collection;

use Armstrong1992\ZplImageToolBox\ImageData\Collection\DecodedImageDataCollection;
use Armstrong1992\ZplImageToolBox\ImageData\DecodedImageDataInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(DecodedImageDataCollection::class)]
final class DecodedImageDataCollectionTest extends TestCase
{
    public function testCollection(): void
    {
        $collection = new DecodedImageDataCollection();

        $this->assertCount(0, $collection);
        $this->assertEquals(0, $collection->count());

        $collection->add($this->createMock(DecodedImageDataInterface::class));
        
        $this->assertCount(1, $collection);
        $this->assertEquals(1, $collection->count());
    }
}
