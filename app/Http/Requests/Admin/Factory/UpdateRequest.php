<?php

namespace App\Http\Requests\Admin\Factory;

use App\DTOs\Factory\FactoryPayloadDTO;
use App\Enums\Factory\FactoryType;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

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
            'type' => [
                'required',
                'string',
                new Enum(FactoryType::class)
            ],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'Zavod nomi',
            'type' => 'Zavod turi',
            'number' => 'Zavod raqami',
        ];
    }

    public function toDto(): FactoryPayloadDTO
    {
        return new FactoryPayloadDTO(
            name: $this->get('name'),
            number: $this->get('number'),
            type: FactoryType::tryFrom($this->get('type'))
        );
    }
}
