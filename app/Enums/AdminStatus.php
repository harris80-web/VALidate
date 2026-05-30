<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class AdminStatus extends Enum
{
    const ACTIVE = 1;
    const INACTIVE = 2;

    public static function getDescription($value): string
    {
        return match ($value) {
            self::ACTIVE => 'Active',
            self::INACTIVE => 'Inactive',
            default => $value,
        };
    } 
}
