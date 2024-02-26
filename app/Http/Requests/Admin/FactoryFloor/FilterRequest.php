<?php

namespace App\Http\Requests\Admin\FactoryFloor;

use App\DTOs\FactoryFloor\FilterDTO;
use App\Enums\Role\Role;
use App\Http\Requests\FilterValidator;
use App\Models\User;
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
            'factory_id' => ['nullable', Rule::exists('factories', 'id')->where('deleted_at')],
            'list' => 'nullable|boolean'
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
        /** @var User $user */
        $user = auth()->user();

        $payload = $this->validated();
        $payload['user'] = $user;

        return new FilterDTO(...$payload);
    }
}
