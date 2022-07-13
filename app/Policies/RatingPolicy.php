<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class RatingPolicy
{
    public static function rate(User $reviewed , Project $project){
        $user = Auth::user();
        return $user->isAdmin($project) && $user->id!==$reviewed->id;
}

}
