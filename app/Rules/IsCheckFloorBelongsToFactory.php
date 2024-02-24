<?php

namespace App\Rules;

use App\Models\FactoryFloor;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Translation\PotentiallyTranslatedString;

class IsCheckFloorBelongsToFactory implements ValidationRule
{
    protected int $factory_id;

    public function __construct(FormRequest $request, string $column)
    {
        $this->factory_id = $request->get($column);
    }

    /**
     * Run the validation rule.
     *
     * @param Closure(string): PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (empty($value)) {
            return;
        }

        $has_not_belonging_floors = FactoryFloor::query()
            ->findMany($value)
            ->where('factory_id', '!=', $this->factory_id)
            ->isNotEmpty();

        $has_not_belonging_floors && $fail("Zavodga tegishli bo'lmagan sex tanlangan");
    }
}
