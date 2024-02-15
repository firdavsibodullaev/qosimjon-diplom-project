<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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
            throw new AuthenticationException("Неправильные данные");
        }

        /** @var User $user */
        $user = $request->user();

        $token = $user->createToken("User");

        return response([
            "token" => $token->plainTextToken,
            "user" => UserResource::make($user)
        ]);
    }

    public function logout(Request $request): Response
    {
        /** @var User $user */
        $user = $request->user();
        $user->currentAccessToken()->delete();

        return response([
            "message" => "Logged out"
        ]);
    }
}
