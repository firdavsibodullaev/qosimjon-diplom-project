<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\FilterRequest;
use App\Http\Requests\Admin\User\StoreRequest;
use App\Http\Requests\Admin\User\UpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;
use Firdavsi\Responses\Http\SuccessEmptyResponse;
use Firdavsi\Responses\Http\SuccessResponse;

class UserController extends Controller
{
    public function __construct(protected UserService $userService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(FilterRequest $request): SuccessResponse
    {
        $users = $this->userService->paginate($request->toDto());

        return new SuccessResponse(
            response: UserResource::collection($users),
            message: 'Foydalanuvchilar ro\'yhati'
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request): SuccessResponse
    {
        $user = $this->userService->create($request->toDto());

        return new SuccessResponse(
            response: UserResource::make($user),
            message: 'Foydalanuvchi yaratildi',
            status: 201
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user): SuccessResponse
    {
        return new SuccessResponse(
            response: UserResource::make($user->load('factoryFloor.factoryRelation', 'roles')),
            message: 'Foydalanuvchi haqida batafsil'
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, User $user): SuccessResponse
    {
        $user = $this->userService->update($user, $request->toDto());

        return new SuccessResponse(
            response: UserResource::make($user),
            message: 'Foydalanuvchi tahrirlandi'
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user): SuccessEmptyResponse
    {
        $this->userService->delete($user);

        return new SuccessEmptyResponse(
            message: 'Foydalanuvchi o\'chirildi'
        );
    }
}
