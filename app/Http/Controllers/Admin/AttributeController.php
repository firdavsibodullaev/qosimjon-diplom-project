<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Attribute\AttributeResource;
use App\Models\Attribute;
use App\Services\AttributeService;
use Firdavsi\Responses\Http\SuccessResponse;
use Illuminate\Http\Request;

class AttributeController extends Controller
{
    public function __construct(protected AttributeService $attributeService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): SuccessResponse
    {
        $attributes = $this->attributeService->get();

        return new SuccessResponse(
            response: AttributeResource::collection($attributes),
            message: 'O\'lchov birliklar ro\'yhati'
        );
    }
}
