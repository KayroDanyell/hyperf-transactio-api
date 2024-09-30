<?php

namespace App\Enum;
enum UserTypesEnum : string
{
    case COMMON = 'common';
    case MERCHANT = 'merchant';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}