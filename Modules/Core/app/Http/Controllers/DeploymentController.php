<?php

namespace Modules\Core\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Core\Services\DeploymentService;

class DeploymentController extends Controller
{
    public function index(DeploymentService $deployment): View
    {
        $data = [
            'deployment' => $deployment->status(),
        ];

        return view('core::deployment.index', $data);
    }

    public function store(Request $request, DeploymentService $deployment): JsonResponse
    {
        return response()->json($deployment->start($request->boolean('restart')));
    }

    public function run(DeploymentService $deployment): JsonResponse
    {
        return response()->json($deployment->runNext());
    }
}
