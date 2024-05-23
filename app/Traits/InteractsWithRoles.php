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

    public static function adminModerator(): string
    {
        return sprintf("role:%s|%s", self::ADMIN->value, self::MODERATOR->value);
    }

    public static function moderator(): string
    {
        return sprintf("role:%s", self::MODERATOR->value);
    }

    public static function adminDirectorWorker(): string
    {
        return sprintf("role:%s|%s|%s", self::ADMIN->value, self::MODERATOR->value, self::WORKER->value);
    }

    public static function moderatorWorker(): string
    {
        return sprintf("role:%s|%s", self::MODERATOR->value, self::WORKER->value);
    }

    public static function directorModeratorWorker(): string
    {
        return sprintf("role:%s|%s|%s", self::DIRECTOR->value, self::MODERATOR->value, self::WORKER->value);
    }
}
