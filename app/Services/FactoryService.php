<?php

namespace App\Services;

use App\DTOs\Factory\FactoryFloorPayloadDTO;
use App\Models\Factory;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class FactoryService
{
    public function paginate(): LengthAwarePaginator
    {
        return Factory::query()->orderBy('number')->paginate(20);
    }

    public function create(FactoryFloorPayloadDTO $payload): Factory
    {
        $factory = new Factory([
            'name' => $payload->name,
            'number' => $payload->number,
            'type' => $payload->type
        ]);
        $factory->save();

        return $factory;
    }

    public function update(Factory $factory, FactoryFloorPayloadDTO $payload): Factory
    {
        $factory->fill([
            'name' => $payload->name,
            'number' => $payload->number,
            'type' => $payload->type
        ]);
        $factory->save();

        return $factory;
    }

    public function delete(Factory $factory): ?bool
    {
        return $factory->delete();
    }
}
