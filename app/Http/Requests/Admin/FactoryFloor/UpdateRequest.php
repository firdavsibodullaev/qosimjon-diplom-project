<?php

namespace App\Http\Requests\Admin\FactoryFloor;

use App\DTOs\FactoryFloor\FactoryFloorPayloadDTO;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
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
            'name' => 'required|string|max:100',
            'number' => 'required|int|digits_between:1,5',
            'factory_id' => [
                'required',
                Rule::exists('factories', 'id')->where('deleted_at')
            ]
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'Sex nomi',
            'factory_id' => 'Zavod',
            'number' => 'Sex raqami',
        ];
    }

    public function toDto(): FactoryFloorPayloadDTO
    {
        return new FactoryFloorPayloadDTO(...$this->validated());
    }
}
