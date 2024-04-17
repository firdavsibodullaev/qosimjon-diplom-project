<?php

namespace App\Http\Resources\FactoryDevice;

use App\Http\Resources\Device\DeviceResource;
use App\Http\Resources\Factory\FactoryResource;
use App\Http\Resources\FactoryFloor\FactoryFloorResource;
use App\Models\FactoryDevice;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property-read FactoryDevice $resource
 */
class FactoryDeviceResource extends JsonResource
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
            'factory' => FactoryResource::make($this->whenLoaded('factory')),
            'factory_floor_id' => FactoryFloorResource::make($this->whenLoaded('factoryFloor')),
            'device_id' => DeviceResource::make($this->whenLoaded('device')),
            'number' => $this->resource->number,
            'full_number' => $this->resource->full_number,
            'status' => $this->resource->status,
            'position' => $this->resource->position,
        ];
    }
}
