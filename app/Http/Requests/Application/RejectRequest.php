<?php

namespace App\Http\Requests\Application;

use App\DTOs\Application\RejectDTO;
use App\Enums\Calibration\Status;
use App\Models\Calibration;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

class RejectRequest extends FormRequest
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
            'comment' => 'required|string|max:1000',
            'document' => [
                'required',
                (new File())->extensions('pdf')->max(10240)
            ]
        ];
    }

    public function attributes(): array
    {
        return [
            'comment' => 'Rad etish sababi',
            'document' => 'Hujjat'
        ];
    }

    public function toDto(): RejectDTO
    {
        return new RejectDTO(...$this->validated());
    }
}
