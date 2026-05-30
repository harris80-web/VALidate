<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class RespondentType extends Enum
{
    const CITIZEN = 1;
    const BUSINESS = 2;
    const GOVERNMENT = 3;

    public static function getDescription($value): string
    {
        return match ($value) {
            self::CITIZEN => 'Citizen',
            self::BUSINESS => 'Business',
            self::GOVERNMENT => 'Government',
            default => $value,
        };
    }
}
