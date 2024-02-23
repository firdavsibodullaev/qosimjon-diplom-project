<?php

namespace App\DTOs\Attribute;

class AttributePayloadDTO
{
    public function __construct(
        public string $name,
        public string $measurement_unit
    )
    {
    }
}
