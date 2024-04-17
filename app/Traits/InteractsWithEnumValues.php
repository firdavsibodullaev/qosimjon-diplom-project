<?php

namespace App\Traits;

trait InteractsWithEnumValues
{
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
