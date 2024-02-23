<?php

namespace App\Services;

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
}
