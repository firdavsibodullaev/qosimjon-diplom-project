<?php

namespace App\Http\Controllers;

use App\Http\Requests\Application\ApproveRequest;
use App\Http\Requests\Application\FilterRequest;
use App\Http\Requests\Application\RejectRequest;
use App\Http\Resources\Application\CalibrationResource;
use App\Models\Calibration;
use App\Models\User;
use App\Services\ApplicationService;
use Firdavsi\Responses\Http\SuccessResponse;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;

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

    public function accept(Calibration $calibration): SuccessResponse
    {
        /** @var User $user */
        $user = auth()->user()->load('factoryFloors.factoryRelation');

        abort_if(!$this->applicationService->hasAccessToAccept($calibration, $user), 403, 'Forbidden');

        abort_if($this->applicationService->applicationHasBeenAccepted($calibration), 403, 'Forbidden');

        $calibration = $this->applicationService->accept($calibration, $user);

        return new SuccessResponse(
            response: CalibrationResource::make($calibration),
            message: 'Ariza qabul qilindi'
        );
    }

    /**
     * @param ApproveRequest $request
     * @param Calibration $calibration
     * @return SuccessResponse
     */
    public function approve(ApproveRequest $request, Calibration $calibration): SuccessResponse
    {
        /** @var User $user */
        $user = auth()->user();

        abort_if(!$this->applicationService->hasPermissionToReact($calibration, $user), 403, 'Forbidden');

        abort_if(!$this->applicationService->applicationHasBeenAccepted($calibration), 403, 'Forbidden');

        $calibration = $this->applicationService->approve($calibration, $request->toDto());

        return new SuccessResponse(
            response: CalibrationResource::make($calibration),
            message: 'Ariza tasdiqlandi'
        );
    }

    /**
     * @param RejectRequest $request
     * @param Calibration $calibration
     * @return SuccessResponse
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public function reject(RejectRequest $request, Calibration $calibration): SuccessResponse
    {
        /** @var User $user */
        $user = auth()->user();

        abort_if(!$this->applicationService->hasPermissionToReact($calibration, $user), 403, 'Forbidden');

        abort_if(!$this->applicationService->applicationHasBeenAccepted($calibration), 403, 'Forbidden');

        $calibration = $this->applicationService->reject($calibration, $request->toDto());

        return new SuccessResponse(
            response: CalibrationResource::make($calibration),
            message: 'Ariza rad etildi'
        );
    }
}
