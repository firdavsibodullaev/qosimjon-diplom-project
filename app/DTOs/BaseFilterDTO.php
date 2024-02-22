<?php

namespace App\DTOs;

abstract class BaseFilterDTO
{
    public function __construct(
        public string $sorter = 'id'
    )
    {
    }
}
