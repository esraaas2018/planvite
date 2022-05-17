<?php

namespace App\Providers;

use App\Models\Task;
use App\Models\User;
use App\Policies\TaskPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Task::class =>TaskPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('view-task',[TaskPolicy::class,'view']);
        Gate::define('create-task',[TaskPolicy::class,'create']);
        Gate::define('adminUpdate-task',[TaskPolicy::class,'adminUpdate']);
        Gate::define('assUpdate-task',[TaskPolicy::class,'assUpdate']);
        Gate::define('delete-task',[TaskPolicy::class,'delete']);
    }
}
