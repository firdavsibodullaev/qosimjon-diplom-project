<?php

namespace App\DTOs\User;

use App\Enums\Role\Role;

class UserPayloadDTO
{
    public function __construct(
        public string  $name,
        public string  $username,
        public Role    $role,
        public ?string $password = null
    )
    {
    }
}
