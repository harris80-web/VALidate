<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class Gender extends Enum
{
    const MALE = 1;
    const FEMALE = 2;

    public static function getDescription($value): string
    {
        return match ($value) {
            self::MALE => 'Male',
            self::FEMALE => 'Female',
            default => $value,
        };
    }
}
