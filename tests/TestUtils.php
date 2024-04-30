<?php

declare(strict_types=1);

/*
 * This file is part of the "Zpl Image Toolbox" library.
 * (c) Paweł Smok (Armstrong) 2024
 *
 * For the full copyright and license information, please view the LICENSE file.
 */

namespace Armstrong1992\ZplImageToolBox\Tests;

final class TestUtils
{
    private const string ASSETS_DIR = __DIR__.'/assets';

    public static function getTestLabel(string $filename): string
    {
        return file_get_contents(self::ASSETS_DIR.'/test_labels/'.$filename);
    }
}
