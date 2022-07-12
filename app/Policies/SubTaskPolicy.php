<?php

namespace App\Policies;

use App\Models\SubTask;
use App\Models\Task;
use App\Models\User;

class SubTaskPolicy
{
    public static function index(User $user, Task $task)
    {
        $project = $task->project;
        $assignee = $task->assignee;
        return $assignee->isParticipant($project);

    }
    public static function store(User $user, Task $task){
        $project = $task->project;
        $assignee = $task->assignee;
        return $user->isAdmin($project) || ($user->id  == $assignee->id);
    }
    public static function show(User $user, SubTask $subtask){
        $project = $subtask->task->project;
        $assignee = $subtask->task->assignee;
        return $assignee->isParticipant($project);
    }
    public static function update(User $user, SubTask $subtask){
        $project = $subtask->task->project;
        $assignee = $subtask->task->assignee;
        return $user->isAdmin($project) || ($user->id  == $assignee->id);
    }
    public static function delete(User $user, SubTask $subtask){
        $project = $subtask->task->project;
        $assignee = $subtask->task->assignee;
        return $user->isAdmin($project) || ($user->id  == $assignee->id);
    }
}
