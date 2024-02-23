<?php

namespace App\Services;

use App\DTOs\Attribute\AttributePayloadDTO;
use App\DTOs\AttributeValue\AttributeValuePayloadDTO;
use App\DTOs\Device\DeviceAttributesDTO;
use App\DTOs\Device\DevicePayloadDTO;
use App\DTOs\Device\FilterDTO;
use App\Models\Device;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class DeviceService
{
    public function __construct(
        protected AttributeService      $attributeService,
        protected AttributeValueService $attributeValueService
    )
    {
    }

    public function paginate(FilterDTO $filter): LengthAwarePaginator
    {
        return Device::filter($filter)->with('attributes')->paginate($filter->total);
    }

    public function create(DevicePayloadDTO $payload): Device
    {
        return DB::transaction(function () use ($payload) {
            /** @var Device $device */
            $device = Device::query()->create(['name' => $payload->name]);

            $device->attributes()->createMany($this->preparePayload($payload->attributes));

            return $device;
        });
    }

    protected function preparePayload(Collection $attributes): array
    {
        $payload = [];

        /** @var DeviceAttributesDTO $attribute */
        foreach ($attributes as $attribute) {
            $payload[] = $this->preparePayloadItem($attribute);
        }

        return $payload;
    }

    protected function preparePayloadItem(DeviceAttributesDTO $attribute): array
    {
        return [
            'measurement_unit' => $attribute->measurement_unit,
            'attribute_id' => $attribute_id = $this->getAttributeId(
                attribute: $attribute->attribute,
                measurement_unit: $attribute->measurement_unit
            ),
            'attribute_value_id' => $this->getValueId(
                value: $attribute->value,
                attribute: $attribute_id
            )
        ];
    }

    protected function getAttributeId(int|string $attribute, string $measurement_unit): int
    {
        if (is_int($attribute)) {
            return $attribute;
        }

        return $this->attributeService->create(new AttributePayloadDTO($attribute, $measurement_unit))->id;
    }

    protected function getValueId(int|string $value, int|string $attribute): int
    {
        if (is_int($value)) {
            return $value;
        }

        return $this->attributeValueService->create(new AttributeValuePayloadDTO($value, $attribute))->id;
    }

    public function update(Device $device, DevicePayloadDTO $payload)
    {
        return DB::transaction(function () use ($device, $payload) {
            $device->fill(['name' => $payload->name]);

            $device->attributes()->forceDelete();

            $device->attributes()->createMany($this->preparePayload($payload->attributes));

            $device->save();

            return $device;
        });
    }
}
