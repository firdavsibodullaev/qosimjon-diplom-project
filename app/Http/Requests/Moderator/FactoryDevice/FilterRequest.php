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
            'sorter' => 'string|nullable',
            'list' => 'nullable|boolean',
            'with-calibration' => 'nullable|boolean',
            'all-calibrations' => 'nullable|boolean',
            'checking-important' => 'nullable|boolean',
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
        $is_checking_important = $this->request->boolean('checking-important');
        return new FilterDTO(
            sorter: $is_checking_important ? null : $this->request->get('sorter', 'id'),
            factory_user: $user,
            list: $this->request->boolean('list'),
            with_calibration: $this->request->boolean('with-calibration'),
            all_calibrations: $this->request->boolean('all-calibrations'),
            checking_important: $is_checking_important,
        );
    }
}
