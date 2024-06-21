<?php

namespace App\DTOs\Device;

use App\DTOs\BaseFilterDTO;

class FilterDTO extends BaseFilterDTO
{
    public function __construct(
        public ?string $sorter = 'id',
        public int     $total = 20,
        public ?string $name = null,
        public bool    $paginate = true,
    )
    {
        parent::__construct($sorter);
    }
}
