<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sprint extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'deadline',
        'project_id',
        'description',
        'status'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function taskDone(){
        return $this->tasks()->where('status_id' ,Status::where('name', 'done')->first()->id)->count();
    }
}
