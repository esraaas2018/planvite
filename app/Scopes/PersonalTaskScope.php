<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;
use function PHPUnit\Framework\callback;

class PersonalTaskScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        $user = Auth::user();
        return $builder->where('user_id' , $user->id);
    }
}
