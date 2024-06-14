<?php

namespace App\Http\Controllers\Moderator;

use App\Enums\Role\Role;
use App\Http\Controllers\Controller;
use App\Http\Requests\Moderator\FactoryDevice\FilterRequest;
use App\Http\Requests\Moderator\FactoryDevice\StoreRequest;
use App\Http\Resources\FactoryDevice\FactoryDeviceResource;
use App\Models\FactoryDevice;
use App\Services\DeviceService;
use App\Services\FactoryDeviceService;
use Firdavsi\Responses\Http\SuccessResponse;

class FactoryDeviceController extends Controller
{
    public function __construct(
        protected DeviceService        $deviceService,
        protected FactoryDeviceService $factoryDeviceService,
    )
    {
        $this->middleware(Role::directorModerator())->except('index');
        $this->middleware(Role::directorModeratorWorker())->only('index');
    }

    public function index(FilterRequest $request): SuccessResponse
    {
        $filter = $request->toDto();

        $devices = $this->factoryDeviceService->paginate($filter);

        return new SuccessResponse(
            response: FactoryDeviceResource::collection($devices),
            message: "Priborlar ro'yhati"
        );
    }

    public function store(StoreRequest $request): SuccessResponse
    {
        $device = $this->factoryDeviceService->create($request->toDto());

        return new SuccessResponse(
            response: FactoryDeviceResource::make($device),
            message: "Yangi pribor qo'shildi",
            status: 201
        );
    }

    public function update(FactoryDevice $factory_device, StoreRequest $request): SuccessResponse
    {
        $device = $this->factoryDeviceService->update($factory_device, $request->toDto());

        return new SuccessResponse(
            response: FactoryDeviceResource::make($device),
            message: "Pribor yangilandi",
        );
    }
}
