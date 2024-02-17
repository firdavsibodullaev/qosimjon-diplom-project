<?php

namespace App\Enums\Role;

enum Role
{
    case ADMIN;
    case TESTER;
    case WORKER;

    public function translate(): string
    {
        return match ($this) {
            self::ADMIN => 'Admin',
            self::TESTER => 'Tekshiruvchi',
            self::WORKER => 'Ishchi',
        };
    }

    public static function translates(): array
    {
        return [
            self::ADMIN->name => 'Admin',
            self::TESTER->name => 'Tekshiruvchi',
            self::WORKER->name => 'Ishchi',
        ];
    }

    public static function forDB(): array
    {
        return [
            [
                'name' => self::ADMIN->name,
                'guard_name' => 'web'
            ],
            [
                'name' => self::TESTER->name,
                'guard_name' => 'web'
            ],
            [
                'name' => self::WORKER->name,
                'guard_name' => 'web'
            ],
        ];
    }
}
