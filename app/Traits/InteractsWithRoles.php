<?php

namespace App\Traits;

trait InteractsWithRoles
{
    public static function admin(): string
    {
        return sprintf("role:%s", self::ADMIN->value);
    }
}
