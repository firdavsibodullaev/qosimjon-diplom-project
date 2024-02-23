<?php

namespace App\Http\Resources\Attribute;

use App\Http\Resources\Device\DeviceResource;
use App\Models\AttributeValue;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property-read AttributeValue $resource
 */
class AttributeValueResource extends JsonResource
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
            'value' => $this->resource->value,
            'attribute' => AttributeResource::make($this->whenLoaded('attribute')),
            'devices' => DeviceResource::collection($this->whenLoaded('devices'))
        ];
    }
}
