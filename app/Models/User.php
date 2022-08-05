<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;

/**
 * @method static orderByDesc(string $string)
 * @method static withoutGlobalScope(string $class)
 * @method static find($user_id)
 * @property mixed $id
 */

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'rating',
        'phone_number',
        'fcm_token',
        'language'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getRatingAttribute(){
        if(($rating = $this->getAttributes()['rating']) === null){
            $rating = $this->ratings()->avg('rating');
            $this->update(['rating' => $rating]);
        }
        return $rating;
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class, 'reviewed_id');
    }

    public function own_projects()
    {
        return $this->hasMany(Project::class);
    }

    public function notifications()
    {
        return $this->hasMany(UserNotification::class);
    }


    public function assigned_tasks()
    {
        return $this->hasMany(Task::class, 'user_id');
    }

    public function personal_tasks()
    {
        return $this->hasMany(PersonalTask::class);
    }

    public function pinnedTasks()
    {
        return $this->belongsToMany(Task::class, 'agenda');
    }

    public function isAdmin(Project $project)
    {
        return $project->user_id == Auth::id();
    }

    public function isAssignee(Project $project)
    {
        $tasks_count_of_user = $this->assigned_tasks()->whereHas('project', function ($q) use ($project) {
            return $q->where('projects.id', $project->id);
        })->count();
        return (bool)$tasks_count_of_user;
    }

    public function isParticipant(Project $project)
    {
        return (bool)$project->participants()->where('users.id', $this->id)->count();

    }

    public function role(Project $project)
    {
        if ($this->isAdmin($project)) {
            $role = 'admin';
        } else if ($this->isAssignee($project)) {
            $role = 'assignee';
        } else {
            $role = 'imposter';
        }
        return $role;
    }

}
