<?php

namespace App\Http\Requests\Admin\Factory;

use App\DTOs\Factory\FilterDTO;
use App\Enums\Factory\FactoryType;
use App\Http\Requests\FilterValidator;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

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
            'list' => 'nullable|boolean',
            'type' => ['nullable', 'string', Rule::enum(FactoryType::class)],
        ];
    }

    public function excludeKeys(): array
    {
        return [
            'page'
        ];
    }

    /**
     * @throws BindingResolutionException
     * @throws ValidationException
     */
    public function toDto(): FilterDTO
    {
        $payload = $this->validated();
        $payload['user'] = $this->request->user();
        $payload['type'] = isset($payload['type']) ? FactoryType::tryFrom($payload['type']) : null;
        return new FilterDTO(...$payload);
    }
}
