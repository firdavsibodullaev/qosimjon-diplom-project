<?php

namespace App\Http\Resources\FactoryDevice;

use App\Http\Resources\Application\CalibrationResource;
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
            'factory_floor' => FactoryFloorResource::make($this->whenLoaded('factoryFloor')),
            'device' => DeviceResource::make($this->whenLoaded('device')),
            'number' => $this->resource->number,
            'full_number' => $this->resource->full_number,
            'status' => $this->resource->status,
            'position' => $this->resource->position,
            'last_checked_at' => $this->resource->last_checked_at?->toDateTimeString(),
            'check_every_time' => $this->resource->check_every_time,
            'latest_calibration' => CalibrationResource::make($this->whenLoaded('lastCalibration')),
            'calibrations' => CalibrationResource::collection($this->whenLoaded('calibrations')),
        ];
    }
}
