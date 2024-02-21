<?php

namespace App\Filters;

use App\DTOs\BaseFilterDTO;
use Illuminate\Database\Eloquent\Builder;

abstract class BaseFilter
{
    public function __construct(
        protected ?string $column = null
    )
    {
    }

    abstract public function filter(Builder $builder, BaseFilterDTO $filters): void;
}
