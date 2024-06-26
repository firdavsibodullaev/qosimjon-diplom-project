<?php

namespace App\Http\Requests\Application;

use App\DTOs\Application\FilterDTO;
use App\Http\Requests\FilterValidator;
use App\Models\User;

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
        /** @var User $user */
        $user = $this->request->user();

        return new FilterDTO(
            sorter: $this->request->get('sorter', 'status,-created_at'),
            q: $this->request->get('q'),
            tester_user: $user
        );
    }
}
