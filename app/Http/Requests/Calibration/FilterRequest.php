<?php

namespace App\Http\Requests\Calibration;

use App\DTOs\Application\FilterDTO;
use App\Http\Requests\FilterValidator;

class FilterRequest extends FilterValidator
{
    public function rules(): array
    {
        return [
            'sorter' => 'nullable|string',
            'q' => 'nullable|string',
        ];
    }

    public function toDto(): FilterDTO
    {
        return new FilterDTO(
            sorter: $this->request->get('sorter', '-status,-created_at'),
            q: $this->request->get('q')
        );
    }
}
