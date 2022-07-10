<?php

namespace App\Policies;

use App\Models\PersonalTask;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PersonalTaskPolicy
{
    use HandlesAuthorization;

    public static function access(User $user,PersonalTask $personal_task)
    {
        return  $user->id == $personal_task->user_id;
    }


}
