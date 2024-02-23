<?php

namespace App\Http\Requests\Admin\Device;

use App\DTOs\Device\DeviceAttributesDTO;
use App\DTOs\Device\DevicePayloadDTO;
use App\Rules\IsValueBelongsToAttribute;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Fluent;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class DeviceRequest extends FormRequest
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
            'name' => 'required|string|max:190',
            'attributes' => 'required|array',
            'attributes.*' => 'required|array|size:3',
            'attributes.*.attribute' => 'required',
            'attributes.*.measurement_unit' => 'required|string|max:20',
            'attributes.*.value' => 'required',
        ];
    }

    public function toDto(): DevicePayloadDTO
    {
        $payload = $this->validated();
        $payload['attributes'] = collect($payload['attributes'])
            ->map(fn(array $attribute) => new DeviceAttributesDTO(...$attribute));

        return new DevicePayloadDTO(...$payload);
    }

    public function attributes(): array
    {
        return [
            'name' => 'Pribor nomi',
            'attributes' => 'Hususiyatlar ro\'yhati',
            'attributes.*' => 'Hususiyat ma\'lumoti',
            'attributes.*.attribute' => 'Hususiyati',
            'attributes.*.measurement_unit' => 'Hususiyat o\'lchov birligi',
            'attributes.*.value' => 'Hususiyat qiymati'
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->sometimes(
            attribute: 'attributes.*.attribute',
            rules: 'string|max:200',
            callback: fn(Fluent $input, Fluent $row) => is_string($row['attribute'])
        );

        $validator->sometimes(
            attribute: 'attributes.*.attribute',
            rules: ['int', Rule::exists('attributes', 'id')->where('deleted_at')],
            callback: fn(Fluent $input, Fluent $row) => is_int($row['attribute'])
        );

        $validator->sometimes(
            attribute: 'attributes.*.value',
            rules: 'string|max:200',
            callback: fn(Fluent $input, Fluent $row) => is_string($row['value'])
        );

        $validator->sometimes(
            attribute: 'attributes.*.value',
            rules: [
                'int',
                Rule::exists('attribute_values', 'id')->where('deleted_at'),
                new IsValueBelongsToAttribute($this)
            ],
            callback: fn(Fluent $input, Fluent $row) => is_int($row['value'])
        );
    }
}
