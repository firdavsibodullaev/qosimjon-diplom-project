<?php

namespace App\Http\Requests\Admin\FactoryFloor;

use App\DTOs\FactoryFloor\FilterDTO;
use App\Http\Requests\FilterValidator;
use Illuminate\Validation\Rule;

class FilterRequest extends FilterValidator
{

    public function rules(): array
    {
        return [
            'sorter' => [
                'nullable',
                'string',
                Rule::in(['id', '-id', 'number', '-number'])
            ]
        ];
    }

    public function excludeKeys(): array
    {
        return [
            'page'
        ];
    }

    public function toDto(): FilterDTO
    {
        return new FilterDTO(...$this->validated());
    }
}