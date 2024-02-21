<?php

namespace App\Http\Requests\Admin\User;

use App\DTOs\User\UserPayloadDTO;
use App\Enums\Role\Role;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Rules\Password;

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
            'last_name' => 'required|string|max:190',
            'first_name' => 'required|string|max:190',
            'username' => [
                'required',
                'string',
                'max:20',
                Rule::unique('users', 'username')->where('deleted_at')
            ],
            'password' => [
                'nullable',
                'string',
                Password::default()
            ],
            'factory_floor_id' => [
                'required',
                'int',
                Rule::exists('factory_floors', 'id')->where('deleted_at')
            ],
            'role' => ['required', 'string', new Enum(Role::class)]
        ];
    }

    public function toDto(): UserPayloadDTO
    {
        $payload = $this->validated();

        $payload['role'] = Role::tryFrom($payload['role']);

        return new UserPayloadDTO(...$payload);
    }
}
