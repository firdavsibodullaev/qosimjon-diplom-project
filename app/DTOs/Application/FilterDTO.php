<?php

namespace App\DTOs\Application;

use App\DTOs\BaseFilterDTO;
use App\Models\User;

class FilterDTO extends BaseFilterDTO
{
    public function __construct(
        public ?User   $user = null,
        public ?string $sorter = 'id',
        public ?string $q = null,
        public ?User   $tester_user = null,
    )
    {
        parent::__construct($this->sorter);
    }
}
