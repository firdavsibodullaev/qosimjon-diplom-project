<?php

namespace App\Services;

use App\DTOs\Device\FilterDTO;
use App\Models\Device;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class DeviceService
{

    public function paginate(FilterDTO $filter): LengthAwarePaginator
    {
        return Device::filter($filter)->paginate($filter->total);
    }
}
