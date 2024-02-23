<?php

namespace App\DTOs\AttributeValue;

class AttributeValuePayloadDTO
{
    public function __construct(
        public string $value,
        public int    $attribute_id
    )
    {
    }
}
