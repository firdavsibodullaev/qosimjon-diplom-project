<?php

namespace App\Traits;

use Illuminate\Support\Arr;

trait InteractsWithEnums
{
    public function is(array|self $enum): bool
    {
        $enum = Arr::wrap($enum);

        return in_array($this, $enum);
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
