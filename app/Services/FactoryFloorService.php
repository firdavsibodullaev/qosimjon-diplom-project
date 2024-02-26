<?php

namespace App\Services;

use App\DTOs\FactoryFloor\FactoryFloorPayloadDTO;
use App\DTOs\FactoryFloor\FilterDTO;
use App\Models\FactoryFloor;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class FactoryFloorService
{
    public function paginate(FilterDTO $filter = new FilterDTO()): Collection|LengthAwarePaginator
    {
        return FactoryFloor::filter($filter)
            ->when(
                value: $filter->list,
                callback: fn(Builder $builder) => $builder->get(),
                default: fn(Builder $builder) => $builder->paginate(20)
            );
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

    public function getByFactoryId(int $factory_id): Collection
    {
        return FactoryFloor::query()->where('factory_id', '=', $factory_id)->get();
    }
}
