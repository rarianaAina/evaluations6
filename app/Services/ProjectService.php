<?php

namespace App\Services;

use App\Models\Project;

class ProjectService
{
    public function getAllProjects()
    {
        return Project::with(['client', 'tasks', 'creator', 'assignee', 'status'])->get();
    }

    public function getProjectById($id)
    {
        return Project::with(['client', 'tasks', 'creator', 'assignee', 'status'])->findOrFail($id);
    }
}