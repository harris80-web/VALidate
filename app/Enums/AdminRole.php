<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class AdminRole extends Enum
{
    const SUPERADMIN = 1;
    const ADMIN = 2;

    public static function getDescription ($value): string
    {
        return match ($value) {
            self::SUPERADMIN => 'Superadmin',
            self::ADMIN => 'Admin',
            default => $value,
        };
    }
}
