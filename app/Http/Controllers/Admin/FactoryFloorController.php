<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\FactoryFloor\StoreRequest;
use App\Http\Resources\FactoryFloor\FactoryFloorResource;
use App\Models\FactoryFloor;
use App\Services\FactoryFloorService;
use Firdavsi\Responses\Http\SuccessEmptyResponse;
use Firdavsi\Responses\Http\SuccessResponse;

class FactoryFloorController extends Controller
{
    public function __construct(protected FactoryFloorService $factoryFloorService)
    {
    }

    public function index(): SuccessResponse
    {
        $floors = $this->factoryFloorService->paginate();

        return new SuccessResponse(
            response: FactoryFloorResource::collection($floors),
            message: 'Sexlar ro\'yhati'
        );
    }

    public function show(FactoryFloor $factoryFloor): SuccessResponse
    {
        return new SuccessResponse(
            response: FactoryFloorResource::make($factoryFloor->load(['factoryRelation', 'users'])),
            message: 'Sex haqida batafsil'
        );
    }

    public function store(StoreRequest $request): SuccessResponse
    {
        $factory = $this->factoryFloorService->create($request->toDto());

        return new SuccessResponse(
            response: FactoryFloorResource::make($factory),
            message: 'Sex yaratildi',
            status: 201
        );
    }

    public function update(StoreRequest $request, FactoryFloor $factoryFloor): SuccessResponse
    {
        $factory = $this->factoryFloorService->update($factoryFloor, $request->toDto());

        return new SuccessResponse(
            response: FactoryFloorResource::make($factory),
            message: 'Sex tahrirlandi'
        );
    }

    public function destroy(FactoryFloor $factoryFloor): SuccessEmptyResponse
    {
        $this->factoryFloorService->delete($factoryFloor);

        return new SuccessEmptyResponse(
            message: 'Sex o\'chirildi'
        );
    }
}
