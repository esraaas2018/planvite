<?php

namespace App\Models;

use App\Scopes\AdminScope;
use App\Scopes\ProjectScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;


/**
 * @method static first()
 * @property mixed $sprints
 */
class Project extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'user_id',
        'deadline',
        'description'
    ];

    public function admin()
    {
        return $this->belongsTo(User::class);
    }

    protected static function booted()
    {
        static::addGlobalScope(new ProjectScope());
    }

    public function sprints()
    {
        return $this->hasMany(Sprint::class);
    }


    public function statuses()
    {
        return $this->belongsToMany(Status::class)->withPivot(['order']);
    }

    public function tasks()
    {
        return $this->hasManyThrough(
            Task::class,
            Sprint::class
        );
    }

    public function personal_tasks()
    {
        return $this->hasMany(PersonalTask::class);
    }

    //bring all users in this project
    public function participants(){
        return $this->belongsToMany(User::class, 'participants');
    }

    public function tasksDoneInProject(){
        $task_done =$this->tasks()->where('status_id' ,Status::where('name', 'done')->first()->id)->count();
        return $this->tasks()->count()==0?0:$task_done / $this->tasks()->count();
    }

    public function tasksDoneInSprint(){
        $task_done =$this->sprints()->where('status',true)->first();
        if($task_done){
            $task_done1 = $task_done->tasks()->where('status_id' ,Status::where('name', 'done')->first()->id)->count();
            return $this->sprints()->where('status',true)->first()->tasks()->count()==0?0:$task_done1 / $this->sprints()->where('status',true)->first()->tasks()->count();
        }
        return 0;
    }
}
