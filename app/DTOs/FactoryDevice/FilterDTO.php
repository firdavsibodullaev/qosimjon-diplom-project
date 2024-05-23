<?php

namespace App\DTOs\FactoryDevice;

use App\DTOs\BaseFilterDTO;
use App\Models\User;

class FilterDTO extends BaseFilterDTO
{
    public function __construct(
        public string $sorter = 'id',
        public int    $total = 20,
        public ?User  $factory_user = null,
        public bool   $list = false
    )
    {
        parent::__construct($sorter);
    }
}
