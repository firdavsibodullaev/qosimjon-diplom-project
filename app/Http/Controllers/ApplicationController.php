<?php

namespace App\Http\Controllers;

use App\Http\Requests\Application\FilterRequest;
use App\Http\Requests\Application\StoreRequest;
use App\Http\Requests\Application\UpdateRequest;
use App\Http\Resources\Application\CalibrationResource;
use App\Models\Calibration;
use App\Services\ApplicationService;
use Firdavsi\Responses\Http\ErrorResponse;
use Firdavsi\Responses\Http\SuccessResponse;

class ApplicationController extends Controller
{
    public function __construct(
        protected ApplicationService $applicationService
    )
    {
    }

    public function index(FilterRequest $request): SuccessResponse
    {
        $list = $this->applicationService->paginate($request->toDto());

        return new SuccessResponse(
            response: CalibrationResource::collection($list),
            message: 'Arizalar ro\'yhati'
        );
    }

    public function store(StoreRequest $request): ErrorResponse|SuccessResponse
    {
        $payload = $request->toDto();

        if ($this->applicationService->hasActiveDeviceStatus($payload->factory_device_id)) {
            return new ErrorResponse(
                message: "Pribor tekshiruvda",
                status: 400
            );
        }

        $application = $this->applicationService->create($payload);

        return new SuccessResponse(
            response: CalibrationResource::make($application),
            message: 'Ariza yaratildi',
            status: 201
        );
    }

    public function update(UpdateRequest $request, Calibration $application): SuccessResponse
    {
        $application = $this->applicationService->update($application, $request->toDto());

        return new SuccessResponse(
            response: CalibrationResource::make($application),
            message: 'Ariza tahrirlandi'
        );
    }
}
