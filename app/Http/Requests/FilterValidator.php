<?php

namespace App\Http\Requests;

use Illuminate\Http\Concerns\InteractsWithInput;
use Illuminate\Http\Request;
use Illuminate\Validation\Factory;

abstract class FilterValidator
{
    use InteractsWithInput;

    private Factory $validator;

    public function __construct(
        protected Request $request
    )
    {
        $this->validator = app('validator');
    }

    public function validated()
    {
        $validator = $this->validator->make($this->request->all(), $this->rules());


        $data = array_filter(
            array: $this->request->all(),
            callback: fn(string $key) => !in_array($key, $this->excludeKeys()),
            mode: ARRAY_FILTER_USE_KEY
        );

        return $validator->fails()
            ? array_filter(
                array: $data,
                callback: fn($key) => !$validator->errors()->has($key),
                mode: ARRAY_FILTER_USE_KEY
            )
            : $validator->validated();
    }

    abstract public function rules(): array;

    public function excludeKeys(): array
    {
        return [];
    }
}
