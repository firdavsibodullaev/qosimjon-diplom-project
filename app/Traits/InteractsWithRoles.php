<?php

namespace App\Traits;

trait InteractsWithRoles
{
    public static function admin(string $guard = 'web'): string
    {
        return sprintf("role:%s,%s", self::ADMIN->name, $guard);
    }
}
