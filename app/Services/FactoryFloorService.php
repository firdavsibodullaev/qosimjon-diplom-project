<?php

namespace App\Services;

use App\DTOs\FactoryFloor\FactoryFloorPayloadDTO;
use App\DTOs\FactoryFloor\FilterDTO;
use App\Models\FactoryFloor;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class FactoryFloorService
{
    public function paginate(FilterDTO $filter = new FilterDTO()): LengthAwarePaginator
    {
        return FactoryFloor::filter($filter)->orderBy('id')->with('factoryRelation')->paginate(20);
    }

    public function create(FactoryFloorPayloadDTO $payload): FactoryFloor
    {
        $factory_floor = new FactoryFloor([
            'name' => $payload->name,
            'factory_id' => $payload->factory_id,
            'number' => $payload->number
        ]);
        $factory_floor->save();

        return $factory_floor;
    }

    public function update(FactoryFloor $factory_floor, FactoryFloorPayloadDTO $payload): FactoryFloor
    {
        $factory_floor->fill([
            'name' => $payload->name,
            'factory_id' => $payload->factory_id,
            'number' => $payload->number
        ]);
        $factory_floor->save();

        return $factory_floor;
    }

    public function delete(FactoryFloor $factory_floor): ?bool
    {
        return $factory_floor->delete();
    }
}
