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
        public array   $factory_floor_id,
        public ?int     $factory_id = null,
        public ?string $password = null
    )
    {
    }
}
