<?php

namespace App\Http\Requests\Admin\User;

use App\DTOs\User\UserPayloadDTO;
use App\Enums\Role\Role;
use App\Rules\IsCheckFloorBelongsToFactory;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Rules\Password;

class StoreRequest extends FormRequest
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
            'last_name' => 'required|string|max:190',
            'first_name' => 'required|string|max:190',
            'username' => [
                'required',
                'string',
                'max:20',
                Rule::unique('users', 'username')->where('deleted_at')
            ],
            'password' => [
                'required',
                'string',
                Password::default()
            ],
            'factory_id' => 'nullable|required_unless:role,admin|int',
            'factory_floor_id' => [
                'array',
                new IsCheckFloorBelongsToFactory($this, 'factory_id')
            ],
            'factory_floor_id.*' => [
                'present',
                'int',
                Rule::exists('factory_floors', 'id')->where('deleted_at')
            ],
            'role' => ['required', 'string', new Enum(Role::class)]
        ];
    }

    public function attributes(): array
    {
        return [
            'last_name' => 'Familiyasi',
            'first_name' => 'Ismi',
            'username' => 'Logini',
            'password' => 'Paroli',
            'factory_id' => 'Zavod',
            'factory_floor_id' => 'Sexlar ro\'yhati',
            'factory_floor_id.*' => 'Sexi',
            'role' => 'Roli',
        ];
    }

    public function toDto(): UserPayloadDTO
    {
        $payload = $this->validated();

        $payload['role'] = Role::tryFrom($payload['role']);

        return new UserPayloadDTO(...$payload);
    }
}
