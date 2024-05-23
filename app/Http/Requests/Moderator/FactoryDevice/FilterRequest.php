<?php

namespace App\Http\Requests\Moderator\FactoryDevice;

use App\DTOs\FactoryDevice\FilterDTO;
use App\Http\Requests\FilterValidator;
use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;

class FilterRequest extends FilterValidator
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'list' => 'nullable|boolean'
        ];
    }

    public function attributes(): array
    {
        return [];
    }

    public function toDto(): FilterDTO
    {
        /** @var User $user */
        $user = auth()->user()->load('factoryFloors');

        return new FilterDTO(
            factory_user: $user,
            list: $this->request->boolean('list')
        );
    }
}
