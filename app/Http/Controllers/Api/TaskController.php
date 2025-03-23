<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\TaskService;
use Illuminate\Http\JsonResponse;

class TaskController extends Controller
{
    private $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    public function index(): JsonResponse
    {
        $tasks = $this->taskService->getAllTasks();
        return response()->json($tasks);
    }

    public function show($id): JsonResponse
    {
        $task = $this->taskService->getTaskById($id);
        return response()->json($task);
    }
}