<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Factory\StoreRequest;
use App\Http\Resources\Factory\FactoryResource;
use App\Models\Factory;
use App\Services\FactoryService;
use Firdavsi\Responses\Http\SuccessEmptyResponse;
use Firdavsi\Responses\Http\SuccessResponse;
use OpenApi\Attributes\Get;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Response;

class FactoryController extends Controller
{
    public function __construct(protected FactoryService $factoryService)
    {
    }

    #[Get(
        path: '/api/v1/admin/factory',
        summary: 'Список заводов',
        tags: ['Factories'],
        responses: [
            new Response(response: 200, description: 'Korxonalar ro\'yhati',content: new JsonContent())
        ]
    )]
    public function index(): SuccessResponse
    {
        $factories = $this->factoryService->paginate();

        return new SuccessResponse(
            response: FactoryResource::collection($factories),
            message: 'Korxonalar ro\'yhati'
        );
    }

    public function show(Factory $factory): SuccessResponse
    {
        return new SuccessResponse(
            response: FactoryResource::make($factory),
            message: 'Korxona ma\'lumotlari'
        );
    }

    public function store(StoreRequest $request): SuccessResponse
    {
        $factory = $this->factoryService->create($request->toDto());

        return new SuccessResponse(
            response: FactoryResource::make($factory),
            message: 'Yangi korxona yaratildi',
            status: 201
        );
    }

    public function update(StoreRequest $request, Factory $factory): SuccessResponse
    {
        $factory = $this->factoryService->update($factory, $request->toDto());

        return new SuccessResponse(
            response: FactoryResource::make($factory),
            message: 'Korxona o\'zgartirildi'
        );
    }

    public function destroy(Factory $factory): SuccessEmptyResponse
    {
        $this->factoryService->delete($factory);

        return new SuccessEmptyResponse(
            message: 'Korxona o\'chirildi'
        );
    }
}
