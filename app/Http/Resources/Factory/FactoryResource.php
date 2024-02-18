<?php

namespace App\Http\Resources\Factory;

use App\Http\Resources\FactoryFloor\FactoryFloorResource;
use App\Models\Factory;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property-read Factory $resource
 */
class FactoryResource extends JsonResource
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
            'type' => $this->resource->type,
            'floors' => FactoryFloorResource::collection($this->whenLoaded('factoryFloors'))
        ];
    }
}
