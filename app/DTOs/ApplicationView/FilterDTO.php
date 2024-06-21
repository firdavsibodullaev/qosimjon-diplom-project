<?php

namespace App\DTOs\ApplicationView;

use App\DTOs\BaseFilterDTO;
use App\Models\User;

class FilterDTO extends BaseFilterDTO
{
    public function __construct(
        public User    $user,
        public ?string $sorter = 'id',
        public ?string $q = null,
    )
    {
        parent::__construct($this->sorter);
    }
}
