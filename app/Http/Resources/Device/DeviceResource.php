<?php

namespace App\Http\Resources\Device;

use App\Http\Resources\Factory\FactoryResource;
use App\Http\Resources\FactoryDevice\FactoryDeviceResource;
use App\Http\Resources\FactoryFloor\FactoryFloorResource;
use App\Models\Device;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property-read Device $resource
 */
class DeviceResource extends JsonResource
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
            'attributes' => AttributeDeviceResource::collection($this->whenLoaded('attributes')),
            'details' => $this->when(
                condition: $this->isRelationLoaded(),
                value: fn() => FactoryDeviceResource::make($this->resource->pivot)
            ),
            'factory' => $this->whenLoaded(
                relationship: 'factory',
                value: fn() => FactoryResource::collection($this->resource->factory)
            ),
            'factory_floor' => $this->whenLoaded(
                relationship: 'factoryFloor',
                value: fn() => FactoryFloorResource::collection($this->resource->factoryFloor)
            ),
        ];
    }

    private function isRelationLoaded(): bool
    {
        return $this->resource->relationLoaded('factory') || $this->resource->relationLoaded('factoryFloor');
    }
}
