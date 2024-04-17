<?php

namespace App\Services;

use App\DTOs\Factory\FactoryPayloadDTO;
use App\DTOs\Factory\FilterDTO;
use App\Models\Factory;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class FactoryService
{
    public function paginate(FilterDTO $filter = new FilterDTO()): Collection|LengthAwarePaginator
    {
        return Factory::filter($filter)
            ->when(
                value: $filter->list,
                callback: fn(Builder $builder) => $builder->get(),
                default: fn(Builder $builder) => $builder->paginate(20)
            );
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

    public function findById(int $factory_id): ?Factory
    {
        /** @var Factory|null $factory */
        $factory = Factory::query()->find($factory_id);

        return $factory;
    }
}
