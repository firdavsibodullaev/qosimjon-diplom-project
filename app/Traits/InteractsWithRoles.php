<?php

namespace App\Traits;

trait InteractsWithRoles
{
    public static function admin(): string
    {
        return sprintf("role:%s", self::ADMIN->value);
    }

    public static function adminDirector(): string
    {
        return sprintf("role:%s|%s", self::ADMIN->value, self::DIRECTOR->value);
    }

    public static function moderator(): string
    {
        return sprintf("role:%s", self::MODERATOR->value);
    }
}
