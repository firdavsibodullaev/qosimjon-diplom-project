<?php

namespace App\DTOs\User;

use App\DTOs\BaseFilterDTO;

class FilterDTO extends BaseFilterDTO
{
    public function __construct(
        public string $sorter = 'id',
        public int    $total = 20,
        public bool   $with_trashed_factory = false,
        public bool   $with_trashed_factory_floor = false,
    )
    {
        parent::__construct($this->sorter);
    }
}
