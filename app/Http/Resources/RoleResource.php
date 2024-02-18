<?php

namespace App\Http\Resources;

use App\Enums\Role\Role;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $role = $this->resource->name;
        return [
            'key' => $role,
            'text' => Role::tryFrom($role)->translate()
        ];
    }
}
