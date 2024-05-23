<?php

namespace App\Http\Resources\Application;

use App\Enums\Calibration\MediaCollection;
use App\Http\Resources\Factory\FactoryResource;
use App\Http\Resources\FactoryDevice\FactoryDeviceResource;
use App\Http\Resources\UserResource;
use App\Models\Calibration;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property-read Calibration $resource
 */
class CalibrationResource extends JsonResource
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
            'applicant' => UserResource::make($this->whenLoaded('applicant')),
            'checker' => UserResource::make($this->whenLoaded('checker')),
            'applicantFactory' => FactoryResource::make($this->whenLoaded('applicantFactory')),
            'checkerFactory' => FactoryResource::make($this->whenLoaded('checkerFactory')),
            'device' => FactoryDeviceResource::make($this->whenLoaded('device')),
            'status' => [
                'key' => $this->resource->status,
                'value' => $this->resource->status->text(),
            ],
            'result' => [
                'key' => $this->resource->result,
                'value' => $this->resource->result?->text(),
            ],
            'created_at' => $this->resource->created_at->toDateTimeString(),
            'deadline' => $this->resource->deadline,
            'checked_at' => $this->resource->checked_at?->toDateTimeString(),
            'comment' => $this->resource->comment,
            'document' => $this->whenLoaded(
                relationship: 'media',
                value: function () {
                    $file = $this->resource->getFirstMedia(MediaCollection::DOCUMENT->value);

                    return [
                        'url' => $file->getUrl(),
                        'name' => $file->file_name,
                        'size' => $file->humanReadableSize
                    ];
                }
            ),
        ];
    }
}
