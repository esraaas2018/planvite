<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\Sprint;
use App\Models\Task;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TaskPolicy
{
    use HandlesAuthorization;

    public static function viewAny(User $user)
    {
        return  false;
    }

    public static function view(User $user,Task $task)
    {
        $project = $task->project;
        if($project && $user->role($project) === 'admin'){
            $allow = true;
        }else if($project && $user->role($project) === 'assignee'){
            $allow = $user->id == $task->user_id;
        }else
            $allow = false;

        return $allow;
    }

    public static function create(User $user,Sprint $sprint)
    {
        $project = $sprint->project;
        return $project && $user->isAdmin($project);
    }

    public static function Update(User $user, Task $task)
    {
        $project = $task->project;
        return $project && $user->isAdmin($project);
    }

    public static function assignToUser(User $user, Project $project, User $assignee){
        return $project && $user->isAdmin($project) && $assignee->isParticipant($project);
    }

    public static function changeStatus(User $user,Task $task)
    {
        $project = $task->project;
        if($project && $user->role($project) === 'admin'){
            $allow = true;
        }else if($project && $user->role($project) === 'assignee'){
            $allow = $user->id == $task->user_id;
        }else
            $allow = false;

        return $allow;
    }

    public static function editAgenda(User $user, Task $task)
    {
        return $user->id == $task->user_id;
    }

    public static function delete(User $user, Task $task)
    {
        $project = $task->project;
        return $project && $user->isAdmin($project);
    }
}
