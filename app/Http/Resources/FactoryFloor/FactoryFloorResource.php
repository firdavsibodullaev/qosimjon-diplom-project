<?php

namespace App\Http\Resources\FactoryFloor;

use App\Http\Resources\Factory\FactoryResource;
use App\Http\Resources\UserResource;
use App\Models\FactoryFloor;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property-read FactoryFloor $resource
 */
class FactoryFloorResource extends JsonResource
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
            'name' => $this->resource->name,
            'number' => $this->resource->number,
            'factory' => FactoryResource::make($this->whenLoaded('factoryRelation')),
            'users' => UserResource::collection($this->whenLoaded('users'))
        ];
    }
}
