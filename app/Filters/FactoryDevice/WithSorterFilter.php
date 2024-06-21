<?php

namespace App\Filters\FactoryDevice;

use App\DTOs\BaseFilterDTO;
use App\DTOs\FactoryDevice\FilterDTO;
use App\Filters\BaseFilter;
use Illuminate\Database\Eloquent\Builder;

class WithSorterFilter extends BaseFilter
{
    public function filter(Builder $builder, BaseFilterDTO $filters): void
    {

        $now = now()->toDateTimeString();
        /** @var FilterDTO $filters */
        $builder->when(
            value: $filters->checking_important,
            callback: fn(Builder $builder) => $builder->orderByRaw("EXTRACT(DAY FROM last_checked_at + (check_every_time || ' months')::INTERVAL - ?::DATE)", [$now])
        );
    }
}
