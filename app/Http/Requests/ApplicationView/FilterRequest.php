<?php

namespace App\Http\Requests\ApplicationView;

use App\DTOs\ApplicationView\FilterDTO;
use App\Http\Requests\FilterValidator;

class FilterRequest extends FilterValidator
{
    public function rules(): array
    {
        return [
            'sorter' => 'nullable|string',
        ];
    }

    public function toDto()
    {
        return new FilterDTO(
            user: auth()->user(),
            sorter: $this->request->get('sorter', '-id'),
        );
    }
}
