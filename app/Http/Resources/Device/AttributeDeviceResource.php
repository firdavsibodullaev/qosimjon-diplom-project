<?php

namespace App\Http\Resources\Device;

use App\Http\Resources\Attribute\AttributeResource;
use App\Http\Resources\Attribute\AttributeValueResource;
use App\Models\AttributeDevice;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property-read AttributeDevice $resource
 */
class AttributeDeviceResource extends JsonResource
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
            'device' => DeviceResource::make($this->whenLoaded('device')),
            'attribute' => AttributeResource::make($this->whenLoaded('attribute')),
            'value' => AttributeValueResource::make($this->whenLoaded('value')),
        ];
    }
}
