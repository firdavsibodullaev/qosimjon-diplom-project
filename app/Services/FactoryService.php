<?php

namespace App\Services;

use App\DTOs\Factory\FactoryPayloadDTO;
use App\Models\Factory;
use Illuminate\Database\Eloquent\Collection;

class FactoryService
{
    public function get(): Collection
    {
        return Factory::query()->get();
    }

    public function create(FactoryPayloadDTO $payload): Factory
    {
        $factory = new Factory([
            'name' => $payload->name,
            'number' => $payload->number,
            'type' => $payload->type
        ]);
        $factory->save();

        return $factory;
    }

    public function update(Factory $factory, FactoryPayloadDTO $payload): Factory
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
