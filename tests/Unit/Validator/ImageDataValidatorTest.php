<?php

declare(strict_types=1);

/*
 * This file is part of the "Zpl Image Toolbox" library.
 * (c) Paweł Smok (Armstrong) 2024
 *
 * For the full copyright and license information, please view the LICENSE file.
 */

namespace Armstrong1992\ZplImageToolBox\Tests\Unit\Validator;

use Armstrong1992\ZplImageToolBox\Validator\Exception\ValidatorException;
use Armstrong1992\ZplImageToolBox\Validator\ImageDataValidator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(ImageDataValidator::class)]
final class ImageDataValidatorTest extends TestCase
{
    #[DataProvider('provideTestData')]
    public function testValidate(
        string $imageData,
        int    $imageDataSize,
        bool   $expectsException

    ): void
    {
        if ($expectsException) {
            $this->expectException(ValidatorException::class);
        } else {
            $this->expectNotToPerformAssertions();
        }

        (new ImageDataValidator($imageData, $imageDataSize))->validate();

    }

    public static function provideTestData(): \Generator
    {
        yield ['', 0, true];
        yield ['A', 1, true];
        yield ['AF', 4, true];
        yield ['ZZ', 1, true];
        yield ['123', 1, true];
        yield ['AFF', 2, true];
        yield ['AF', 1, false];
        yield ['AFF4', 2, false];
    }
}
