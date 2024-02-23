<?php

namespace App\Services;

use App\DTOs\Attribute\AttributePayloadDTO;
use App\Models\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AttributeService
{

    public function get(): Collection
    {
        return Attribute::query()
            ->with('values', fn(HasMany $hasMany) => $hasMany->orderBy('value'))
            ->orderBy('name')
            ->get();
    }

    public function create(AttributePayloadDTO $payload): Attribute
    {
        $attribute = new Attribute([
            'name' => $payload->name,
            'measurement_unit' => $payload->measurement_unit
        ]);
        $attribute->save();

        return $attribute;
    }
}
