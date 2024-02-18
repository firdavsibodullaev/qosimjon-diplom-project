<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Role\Role;
use App\Http\Controllers\Controller;
use Firdavsi\Responses\Http\SuccessResponse;

class RoleController extends Controller
{
    public function __invoke(): SuccessResponse
    {
        return new SuccessResponse(
            response: response(Role::translates())
        );
    }
}
