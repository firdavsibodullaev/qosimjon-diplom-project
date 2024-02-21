<?php

namespace App\DTOs\Factory;

use App\DTOs\BaseFilterDTO;

class FilterDTO extends BaseFilterDTO
{
    public function __construct(
        public ?string $sorter = 'number'
    )
    {
    }
}
