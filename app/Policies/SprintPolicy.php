<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\Sprint;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SprintPolicy
{
    public static function view(User  $user, Sprint $sprint)
    {
        $project = $sprint->project;
        return $user->isParticipant($project)||$user->isAdmin($project);
    }

    public static function create(User $user, Project $project)
    {
        return $user->isAdmin($project);
    }

    public static function update(User $user, Sprint $sprint)
    {
        $project = $sprint->project;
        return $project && $user->isAdmin($project);
    }
    public static function delete(User $user, Sprint $sprint)
    {
        $project = $sprint->project;
        return $project && $user->isAdmin($project);
    }

    public static function run(User $user, Sprint $sprint)
    {
        $project = $sprint->project;
        return $project && $user->isAdmin($project);
    }

    public static function off(User $user, Sprint $sprint)
    {
        $project = $sprint->project;
        return $project && $user->isAdmin($project);
    }

    public static function recycle(User $user, Sprint $sprint)
    {
        $project = $sprint->project;
        return $project && $user->isAdmin($project);
    }
}
