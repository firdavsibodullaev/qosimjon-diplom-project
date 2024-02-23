<?php

namespace App\Services;

use App\DTOs\AttributeValue\AttributeValuePayloadDTO;
use App\Models\AttributeValue;

class AttributeValueService
{
    public function create(AttributeValuePayloadDTO $payload): AttributeValue
    {
        $attribute_value = new AttributeValue([
            'value' => $payload->value,
            'attribute_id' => $payload->attribute_id
        ]);
        $attribute_value->save();

        return $attribute_value;
    }
}
