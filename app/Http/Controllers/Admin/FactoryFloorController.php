<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\FactoryFloor\FactoryFloorRequest;
use App\Http\Requests\Admin\FactoryFloor\FilterRequest;
use App\Http\Resources\FactoryFloor\FactoryFloorResource;
use App\Models\FactoryFloor;
use App\Services\FactoryFloorService;
use Firdavsi\Responses\Http\SuccessEmptyResponse;
use Firdavsi\Responses\Http\SuccessResponse;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FactoryFloorController extends Controller
{
    public function __construct(protected FactoryFloorService $factoryFloorService)
    {
    }

    public function index(FilterRequest $request): SuccessResponse
    {
        $floors = $this->factoryFloorService->paginate($request->toDto());

        return new SuccessResponse(
            response: FactoryFloorResource::collection($floors),
            message: 'Sexlar ro\'yhati'
        );
    }

    public function show(FactoryFloor $factory_floor): SuccessResponse
    {
        return new SuccessResponse(
            response: FactoryFloorResource::make(
                $factory_floor->load(relations: [
                    'factoryRelation' => fn(BelongsTo $belongsTo) => $belongsTo->withTrashed(),
                    'users'
                ])
            ),
            message: 'Sex haqida batafsil'
        );
    }

    public function store(FactoryFloorRequest $request): SuccessResponse
    {
        $factory = $this->factoryFloorService->create($request->toDto());

        return new SuccessResponse(
            response: FactoryFloorResource::make($factory),
            message: 'Sex yaratildi',
            status: 201
        );
    }

    public function update(FactoryFloorRequest $request, FactoryFloor $factory_floor): SuccessResponse
    {
        $factory = $this->factoryFloorService->update($factory_floor, $request->toDto());

        return new SuccessResponse(
            response: FactoryFloorResource::make($factory),
            message: 'Sex tahrirlandi'
        );
    }

    public function destroy(FactoryFloor $factory_floor): SuccessEmptyResponse
    {
        $this->factoryFloorService->delete($factory_floor);

        return new SuccessEmptyResponse(
            message: 'Sex o\'chirildi'
        );
    }
}
