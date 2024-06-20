<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApplicationView\FilterRequest;
use App\Services\ApplicationViewService;

class ApplicationViewController extends Controller
{
    public function __construct(protected ApplicationViewService $applicationViewService)
    {
    }

    public function __invoke(FilterRequest $request)
    {
        $list = $this->applicationViewService->paginate($request->toDto());
    }
}
