<?php

declare(strict_types=1);

/*
 * This file is part of the "Zpl Image Toolbox" library.
 * (c) Paweł Smok (Armstrong) 2024
 *
 * For the full copyright and license information, please view the LICENSE file.
 */

namespace Armstrong1992\ZplImageToolBox\Utils;

/**
 * @internal
 */
final class StringUtils
{
    public static function stripWhiteChars(string $inString): string
    {
        return preg_replace('/\s+/', '', trim($inString));
    }
}
