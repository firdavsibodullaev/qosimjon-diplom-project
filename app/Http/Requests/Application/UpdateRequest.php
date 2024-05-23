<?php

namespace App\Http\Requests\Application;

use App\DTOs\Application\ApplicationDTO;
use App\Enums\Calibration\Status;
use App\Enums\Factory\FactoryType;
use App\Models\Calibration;
use App\Models\Factory;
use App\Models\FactoryDevice;
use App\Models\User;
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
        /** @var Calibration $application */
        $application = $this->route('application');

        return $application->status->is(Status::NEW) && $application->applicant_id === auth()->id();
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
                'int',
                Rule::exists((new FactoryDevice)->getTable(), 'id')
            ],
            'factory_id' => [
                'required',
                'int',
                Rule::exists((new Factory())->getTable(), 'id')
                    ->where('type', FactoryType::TESTER)
                    ->whereNull('deleted_at')
            ],
            'document' => 'nullable|file|mimes:pdf|max:' . (1024 * 10),
        ];
    }

    public function attributes(): array
    {
        return [
            'device_id' => 'Pribor',
            'factory_id' => 'Tekshiruvchi korxona',
            'document' => 'Hujjat'
        ];
    }

    public function toDto(): ApplicationDTO
    {
        /** @var User $user */
        $user = auth()->user()->load('factoryFloors');

        $payload = $this->validated();

        /** @var Calibration $calibration */
        $calibration = $this->route('application');

        return new ApplicationDTO(
            factory_device_id: $payload['device_id'],
            applicant_factory_id: $user->factoryFloors->first()->factory_id,
            applicant_id: $user->id,
            checker_factory_id: $payload['factory_id'],
            status: Status::NEW,
            deadline: $calibration->deadline,
            document: $payload['document'] ?? null
        );
    }
}
