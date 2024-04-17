<?php

namespace App\Filters\Device;


use App\DTOs\BaseFilterDTO;
use App\DTOs\Device\FilterDTO;
use App\Filters\BaseFilter;
use Illuminate\Database\Eloquent\Builder;

class FilterName extends BaseFilter

{
    public function filter(Builder $builder, BaseFilterDTO $filters): void
    {
        /** @var FilterDTO $filters */
        $builder
            ->when(
                value: $filters->name,
                callback: fn(Builder $builder) => $builder
                    ->whereRaw('LOWER(name) like ?', "%$filters->name%")
            );
    }
}
