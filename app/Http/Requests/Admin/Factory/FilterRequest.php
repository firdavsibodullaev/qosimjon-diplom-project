<?php

namespace App\Http\Requests\Admin\Factory;

use App\DTOs\Factory\FilterDTO;
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
