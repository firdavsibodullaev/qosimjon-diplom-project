<?php

namespace App\Enums\Role;

use App\Traits\InteractsWithRoles;

enum Role: string
{
    use InteractsWithRoles;

    case ADMIN = 'admin';
    case TESTER = 'tester';
    case WORKER = 'worker';
    case MODERATOR = 'moderator';

    public function translate(): string
    {
        return match ($this) {
            self::ADMIN => 'Admin',
            self::TESTER => 'Tekshiruvchi',
            self::WORKER => 'Ishchi',
            self::MODERATOR => 'Moderator',
        };
    }

    public static function translates(): array
    {
        return [
            self::ADMIN->value => 'Admin',
            self::TESTER->value => 'Tekshiruvchi',
            self::WORKER->value => 'Ishchi',
            self::MODERATOR->value => 'Moderator',
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
            [
                'name' => self::MODERATOR->value,
                'guard_name' => 'web'
            ],
        ];
    }

    public function is(Role $role): bool
    {
        return $this === $role;
    }
}
