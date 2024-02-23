<?php

namespace App\Rules;

use App\Http\Requests\Admin\Device\StoreRequest;
use App\Models\AttributeValue;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class IsValueBelongsToAttribute implements ValidationRule
{
    public function __construct(protected StoreRequest $request)
    {
    }

    /**
     * Run the validation rule.
     *
     * @param Closure(string): PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $index = explode(".", $attribute)[1];

        $attribute_id = $this->request->input("attributes.$index.attribute");

        !$this->checkExistence($attribute_id, $value) && $fail("Qiymat hususiyatga tegishli emas");
    }

    private function checkExistence(int $attribute_id, int $value_id): bool
    {
        return AttributeValue::query()
            ->whereKey($value_id)
            ->where('attribute_id', $attribute_id)
            ->exists();
    }
}
