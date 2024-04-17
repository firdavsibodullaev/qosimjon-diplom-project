<?php

namespace App\Http\Requests\Moderator\FactoryDevice;

use App\DTOs\FactoryDevice\DevicePayloadDTO;
use App\Enums\FactoryDevice\Position;
use App\Enums\FactoryDevice\Status;
use App\Models\FactoryFloor;
use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'device_id' => [
                'required',
                'integer',
                Rule::exists('devices','id')->where('deleted_at')
            ],
            'number' => 'required|integer|digits_between:1,4',
            'factory_floor_id' => [
                'nullable',
                'integer',
                Rule::exists(FactoryFloor::class, 'id'),
            ],
            'status' => ['required', 'string', Rule::enum(Status::class)],
            'position' => ['required', 'string', Rule::enum(Position::class)],
        ];
    }

    public function toDto(): DevicePayloadDTO
    {
        $validated = $this->validated();

        /** @var User $user */
        $user = auth()->user()->load('factoryFloors');

        return new DevicePayloadDTO(
            device_id: $validated['device_id'],
            number: $validated['number'],
            factory_id: $user->factoryFloors->first()->factory_id,
            status: Status::tryFrom($validated['status']),
            position: Position::tryFrom($validated['position']),
            factory_floor_id: $validated['factory_floor_id'] ?? null,

        );
    }
}
