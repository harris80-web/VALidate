<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class QuestionCategoryType extends Enum
{
    const CITIZEN_CHARTER = 1;
    const SERVICE_QUALITY_DIMENSION = 2;
}
