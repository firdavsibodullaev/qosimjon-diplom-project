<?php

namespace App\Enums\Role;

use App\Traits\InteractsWithEnums;
use App\Traits\InteractsWithRoles;

enum Role: string
{
    use InteractsWithRoles, InteractsWithEnums;

    case ADMIN = 'admin';
    case TESTER = 'tester';
    case DIRECTOR = 'director';
    case MODERATOR = 'moderator';
    case WORKER = 'worker';

    public function translate(): string
    {
        return match ($this) {
            self::ADMIN => 'Admin',
            self::TESTER => 'Tekshiruvchi',
            self::DIRECTOR => 'Direktor',
            self::MODERATOR => 'Moderator',
            self::WORKER => 'Ishchi',
        };
    }

    public static function translates(): array
    {
        return [
            self::ADMIN->value => 'Admin',
            self::TESTER->value => 'Tekshiruvchi',
            self::DIRECTOR->value => 'Direktor',
            self::MODERATOR->value => 'Moderator',
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
                'name' => self::DIRECTOR->value,
                'guard_name' => 'web'
            ],
            [
                'name' => self::MODERATOR->value,
                'guard_name' => 'web'
            ],
            [
                'name' => self::WORKER->value,
                'guard_name' => 'web'
            ],
        ];
    }

    public function forRole(): array
    {
        return match ($this) {
            self::ADMIN => array_column(self::cases(), 'value'),
            self::DIRECTOR => [self::MODERATOR->value, self::WORKER->value],
            self::MODERATOR => [self::WORKER->value],
            default => []
        };
    }
}
