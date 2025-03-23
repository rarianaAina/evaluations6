<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ProjectService;
use Illuminate\Http\JsonResponse;

class ProjectController extends Controller
{   
    private $projectService;

    public function __construct(ProjectService $projectService)
    {
        $this->projectService = $projectService;
    }

    public function index(): JsonResponse
    {
        $projects = $this->projectService->getAllProjects();
        return response()->json($projects);
    }

    public function show($id): JsonResponse
    {
        $project = $this->projectService->getProjectById($id);
        return response()->json($project);
    }
}