<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Device\FilterRequest;
use App\Http\Requests\Admin\Device\StoreRequest;
use App\Http\Resources\Device\DeviceResource;
use App\Models\Device;
use App\Services\DeviceService;
use Firdavsi\Responses\Http\SuccessResponse;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    public function __construct(protected DeviceService $deviceService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(FilterRequest $request): SuccessResponse
    {
        $devices = $this->deviceService->paginate($request->toDto());

        return new SuccessResponse(
            response: DeviceResource::collection($devices),
            message: 'Priporlar ro\'yhati'
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request): SuccessResponse
    {
        $device = $this->deviceService->create($request->toDto());

        return new SuccessResponse(
            response: DeviceResource::make($device),
            message: 'Pribor yaratildi',
            status: 201
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Device $device)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Device $device)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Device $device)
    {
        //
    }
}
