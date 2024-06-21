<?php

namespace App\Http\Controllers;

use App\Http\Requests\Calibration\FilterRequest;
use App\Http\Requests\Calibration\StoreRequest;
use App\Http\Requests\Calibration\UpdateRequest;
use App\Http\Resources\Application\CalibrationResource;
use App\Models\Calibration;
use App\Services\CalibrationService;
use Firdavsi\Responses\Http\ErrorResponse;
use Firdavsi\Responses\Http\SuccessResponse;

class CalibrationController extends Controller
{
    public function __construct(
        protected CalibrationService $calibrationService
    )
    {
    }

    public function index(FilterRequest $request): SuccessResponse
    {
        $list = $this->calibrationService->paginate($request->toDto());

        return new SuccessResponse(
            response: CalibrationResource::collection($list),
            message: 'Arizalar ro\'yhati'
        );
    }

    public function store(StoreRequest $request): ErrorResponse|SuccessResponse
    {
        $payload = $request->toDto();

        if (!$this->calibrationService->hasActiveDeviceStatus($payload->factory_device_id)) {
            return new ErrorResponse(
                message: "Pribor tekshiruvda",
                status: 400
            );
        }

        $application = $this->calibrationService->create($payload);

        return new SuccessResponse(
            response: CalibrationResource::make($application),
            message: 'Ariza yaratildi',
            status: 201
        );
    }

    public function update(UpdateRequest $request, Calibration $application): SuccessResponse
    {
        $application = $this->calibrationService->update($application, $request->toDto());

        return new SuccessResponse(
            response: CalibrationResource::make($application),
            message: 'Ariza tahrirlandi'
        );
    }
}
