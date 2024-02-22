<?php

namespace App\DTOs\User;

use App\Enums\Role\Role;

class UserPayloadDTO
{
    public function __construct(
        public string  $last_name,
        public string  $first_name,
        public string  $username,
        public Role    $role,
        public int     $factory_floor_id,
        public ?string $password = null
    )
    {
    }
}
