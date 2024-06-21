<?php

namespace App\Filters;

use App\DTOs\BaseFilterDTO;
use Illuminate\Database\Eloquent\Builder;

class Sorter extends BaseFilter
{
    public function filter(Builder $builder, BaseFilterDTO $filters): void
    {
        $builder->when(
            value: $filters->sorter,
            callback: function (Builder $builder, string $sorter) {
                collect(explode(',', $sorter))
                    ->map(
                        callback: fn(string $sorter_item) => $builder->orderBy(
                            column: str($sorter_item)->replace('-', ''),
                            direction: !str($sorter_item)->match('/^\-/')->toString() ? 'asc' : 'desc')
                    );
            }
        );
    }
}
