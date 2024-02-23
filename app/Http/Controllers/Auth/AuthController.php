<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Firdavsi\Responses\Http\SuccessEmptyResponse;
use Firdavsi\Responses\Http\SuccessResponse;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest:sanctum')->only('login');
        $this->middleware('auth:sanctum')->only('logout');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string'
        ]);

        if (!Auth::attempt($credentials)) {
            throw new AuthenticationException(__('auth.failed'));
        }

        /** @var User $user */
        $user = $request->user();

        $token = $user->createToken("User");

        return new SuccessResponse(
            response: response([
                "token" => $token->plainTextToken,
                "user" => UserResource::make($user->load(['roles', 'factoryFloor.factoryRelation']))
            ]),
            message: 'Tizimga kirdingiz'
        );
    }

    public function logout(Request $request): SuccessEmptyResponse
    {
        /** @var User $user */
        $user = $request->user();
        $user->currentAccessToken()->delete();

        return new SuccessEmptyResponse(
            message: 'Tizimdan chiqdingiz'
        );
    }
}
