<?php
declare(strict_types=1);

namespace App\Util;

final class StringConverter
{
    /**
     * Converts comma separated list of values as a trimmed array
     */
    public static function envStringToArray(string $str): array
    {
        if (trim($str) === '') {
            return [];
        }

        $arr = explode(',', trim($str));
        $arr = array_map('trim', $arr);
        return $arr;
    }
}