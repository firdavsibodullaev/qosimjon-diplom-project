<?php

namespace App\Enums\Role;

use App\Traits\InteractsWithRoles;

enum Role: string
{
    use InteractsWithRoles;

    case ADMIN = 'admin';
    case TESTER = 'tester';
    case WORKER = 'worker';

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
            self::ADMIN->value => 'Admin',
            self::TESTER->value => 'Tekshiruvchi',
            self::WORKER->value => 'Ishchi',
        ];
    }

    public static function forDB(): array
    {
        return [
            [
                'name' => self::ADMIN->value,
                'guard_name' => 'web'
            ],
            [
                'name' => self::TESTER->value,
                'guard_name' => 'web'
            ],
            [
                'name' => self::WORKER->value,
                'guard_name' => 'web'
            ],
        ];
    }
}
