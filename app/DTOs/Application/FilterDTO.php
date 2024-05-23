<?php

namespace App\DTOs\Application;

use App\DTOs\BaseFilterDTO;

class FilterDTO extends BaseFilterDTO
{
    public function __construct(
        public string  $sorter = 'id',
        public ?string $q = null,
    )
    {
        parent::__construct($this->sorter);
    }
}
