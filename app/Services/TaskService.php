<?php

namespace App\Services;

use App\Models\Task;

class TaskService
{
    public function getAllTasks()
    {
        return Task::with(['project', 'client', 'creator', 'user', 'status'])->get();
    }

    public function getTaskById($id)
    {
        return Task::with(['project', 'client', 'creator', 'user', 'status'])->findOrFail($id);
    }
}