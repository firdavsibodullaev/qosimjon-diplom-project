<?php

namespace App\Services;

use App\DTOs\FactoryDevice\DevicePayloadDTO;
use App\DTOs\FactoryDevice\FilterDTO;
use App\Models\FactoryDevice;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class FactoryDeviceService
{
    public function __construct(
        protected FactoryService      $factoryService,
        protected FactoryFloorService $factoryFloorService
    )
    {
    }

    public function paginate(FilterDTO $filter): LengthAwarePaginator
    {
        return FactoryDevice::filter($filter)
            ->with([
                'factory',
                'factoryFloor',
                'device.attributes',
            ])
            ->paginate($filter->total);
    }

    public function create(DevicePayloadDTO $payload): FactoryDevice
    {
        $number = $this->generateNumber($payload->factory_id, $payload->number, $payload->factory_floor_id);

        $device = new FactoryDevice([
            'factory_id' => $payload->factory_id,
            'factory_floor_id' => $payload->factory_floor_id,
            'device_id' => $payload->device_id,
            'number' => $payload->number,
            'full_number' => $number,
            'status' => $payload->status,
            'position' => $payload->position,
        ]);

        $device->save();

        $device->load(['factory', 'factoryFloor']);

        return $device;
    }

    protected function generateNumber(int $factory_id, int $number, ?int $factory_floor_id = null): string
    {
        $result = "";

        if ($factory_floor_id) {
            $floor = $this->factoryFloorService->findById($factory_floor_id)->load('factoryRelation');
            $result .= "{$floor->factoryRelation->number}$floor->number";
        } else {
            $factory = $this->factoryService->findById($factory_id);
            $result .= "$factory->number";
        }

        $result .= $number;

        return $result;
    }

    public function update(FactoryDevice $device, DevicePayloadDTO $payload): FactoryDevice
    {
        $number = $this->generateNumber($payload->factory_id, $payload->number, $payload->factory_floor_id);

        $device->fill([
            'factory_id' => $payload->factory_id,
            'factory_floor_id' => $payload->factory_floor_id,
            'device_id' => $payload->device_id,
            'number' => $payload->number,
            'full_number' => $number,
            'status' => $payload->status,
            'position' => $payload->position,
        ]);

        $device->save();

        $device->load(['factory', 'factoryFloor']);

        return $device;
    }
}
