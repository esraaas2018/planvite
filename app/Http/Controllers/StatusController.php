<?php

namespace App\Http\Controllers;

use App\Http\Requests\StatusStoreRequest;
use App\Http\Resources\StatusResource;
use App\Models\Project;
use App\Models\Status;
use App\Models\Task;
use Illuminate\Http\Request;

class StatusController extends Controller
{
    public function index(Project $project){
        $statuses = $project->statuses()->withPivot('order')->orderBy('order')->get();
        return apiResponse(StatusResource::collection($statuses));
    }

    public function store(Project $project){
        $pending = Status::firstOrCreate(['name' => 'pending']);
        $last_order = $project->statuses()->orderByDesc('order')->get()->skip(1)->first()->order;
        $project->statuses()->attach($pending->id, ['order' => $last_order + 1]);
        return apiResponse(null, "status created successfully", 201);
    }

    public function delete(Project $project, Status $status){
        if($status->name == 'pending' || $status->name == 'done')
            abort(403, "unauthorized action");
        
        $related_tasks = Task::whereHas('sprint', function($q) use ($project) {
            return $q->whereHas('project', function($q) use ($project) {
                return $q->where('projects.id', $project->id);
            });
        })->where('status_id', $status->id);

        $related_tasks->map(function($task){
            $task->update('status_id', Status::where('name', 'pending')->first()->id);
        });

        $project->statuses()->detach($status->id);
        return apiResponse(null, "status created successfully", 201);
    }
}
