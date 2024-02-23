<?php

namespace App\Http\Resources\Attribute;

use App\Models\Attribute;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property-read Attribute $resource
 */
class AttributeResource extends JsonResource
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
            'measurement_unit' => $this->resource->measurement_unit,
            'values' => AttributeValueResource::collection($this->whenLoaded('values'))
        ];
    }
}
