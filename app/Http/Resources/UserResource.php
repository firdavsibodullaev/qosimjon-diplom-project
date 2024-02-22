<?php

namespace App\Http\Resources;

use App\Http\Resources\FactoryFloor\FactoryFloorResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property-read User $resource
 */
class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'last_name' => $this->resource->last_name,
            'first_name' => $this->resource->first_name,
            'name' => $this->resource->name,
            'username' => $this->resource->username,
            'roles' => RoleResource::collection($this->whenLoaded('roles')),
            'floor' => FactoryFloorResource::make($this->whenLoaded('factoryFloor'))
        ];
    }
}
