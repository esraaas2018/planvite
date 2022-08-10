<?php

namespace App\Models;

use App\Scopes\TaskScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Auth;
use Znck\Eloquent\Traits\BelongsToThrough;

/**
 * @method static withoutGlobalScope(string $class)
 */
class Task extends Model
{
    use HasFactory, BelongsToThrough;

    protected $fillable = [
        'name',
        'deadline',
        'sprint_id',
        'user_id',
        'status_id',
        'description'
    ];

    protected static function booted()
    {
        static::addGlobalScope(new TaskScope());

    }

    public function sprint()
    {
        return $this->belongsTo(Sprint::class);
    }

    public function project()
    {
        return $this->belongsToThrough(Project::class, Sprint::class);
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // bring all the tasks of this user

    public function scopeOfUser(Builder $query)
    {
        $user = Auth::user();
        return $query->whereHas('assignee', function ($q) use ($user) {
            return $q->where('tasks.user_id', $user->id);
        });
    }

    public function scopeOfProject(Builder $query, Project $project)
    {
        return $query->whereHas('project', function ($q) use ($project) {
            return $q->where('projects.id', $project->id);
        });
    }

    public function status(){
        return $this->belongsTo(Status::class);
    }

    public function subtasks()
    {
        return $this->hasMany(SubTask::class);
    }


}
