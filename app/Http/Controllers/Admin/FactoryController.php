<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Role\Role;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Factory\FactoryRequest;
use App\Http\Requests\Admin\Factory\FilterRequest;
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
        $this->middleware(Role::admin())->except('index');
        $this->middleware(Role::adminDirector())->only('index');
    }

    #[Get(
        path: '/api/v1/admin/factory',
        summary: 'Список заводов',
        tags: ['Factories'],
        responses: [
            new Response(response: 200, description: 'Korxonalar ro\'yhati', content: new JsonContent())
        ]
    )]
    public function index(FilterRequest $request): SuccessResponse
    {
        $factories = $this->factoryService->paginate($request->toDto());

        return new SuccessResponse(
            response: FactoryResource::collection($factories),
            message: 'Korxonalar ro\'yhati'
        );
    }

    public function show(Factory $factory): SuccessResponse
    {
        return new SuccessResponse(
            response: FactoryResource::make($factory->load('factoryFloors')),
            message: 'Korxona ma\'lumotlari'
        );
    }

    public function store(FactoryRequest $request): SuccessResponse
    {
        $factory = $this->factoryService->create($request->toDto());

        return new SuccessResponse(
            response: FactoryResource::make($factory),
            message: 'Yangi korxona yaratildi',
            status: 201
        );
    }

    public function update(FactoryRequest $request, Factory $factory): SuccessResponse
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
