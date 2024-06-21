<?php

namespace App\Http\Requests\Moderator\FactoryDevice;

use App\DTOs\FactoryDevice\DevicePayloadDTO;
use App\Enums\FactoryDevice\Position;
use App\Enums\FactoryDevice\Status;
use App\Models\FactoryDevice;
use App\Models\FactoryFloor;
use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\RequiredIf;

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
        /** @var FactoryDevice $device */
        $device = $this->route('factory_device');
        return [
            'device_id' => [
                'required',
                'integer',
                Rule::exists('devices', 'id')->where('deleted_at')
            ],
            'number' => 'required|integer|digits_between:1,4',
            'factory_floor_id' => [
                'nullable',
                'integer',
                Rule::exists(FactoryFloor::class, 'id'),
            ],
            'status' => [
                'nullable',
                new RequiredIf($device->lastCalibration()->doesntExist()),
                'string',
                Rule::enum(Status::class)
            ],
            'position' => ['required', 'string', Rule::enum(Position::class)],
            'check_every_time' => 'required|int|max:255'
        ];
    }

    public function attributes(): array
    {
        return [
            'device_id' => 'Pribor',
            'number' => 'Pribor raqami',
            'factory_floor_id' => 'Sex',
            'status' => 'Holati',
            'position' => 'Joyi',
            'check_every_time' => 'Har necha oyda tekshiruvdan o\'tkazish'
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
            check_every_time: $validated['check_every_time'],
            factory_floor_id: $validated['factory_floor_id'] ?? null
        );
    }
}
