<?php

namespace App\Http\Requests\Admin\User;

use App\DTOs\User\FilterDTO;
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
            ],
            'total' => ['nullable', 'int']
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
        $payload = $this->validated();
        $payload['user'] = auth()->user();

        return new FilterDTO(...$payload);
    }
}
