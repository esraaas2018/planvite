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

    public function store(StatusStoreRequest $request, Project $project){
        $pending = Status::firstOrCreate(['name' => $request->name]);
        $last_order = $project->statuses()->orderByDesc('order')->get()->skip(1)->first()->order;
        $project->statuses()->attach($pending->id, ['order' => $last_order + 1]);

        $statuses = $project->statuses()->withPivot('order')->orderBy('order')->get();
        return apiResponse(StatusResource::collection($statuses), 201);
    }

    public function delete(Project $project, Status $status){
        if($status->name == 'pending' || $status->name == 'done')
            abort(403, "unauthorized action");

        $related_tasks = Task::whereHas('sprint', function($q) use ($project) {
            return $q->whereHas('project', function($q) use ($project) {
                return $q->where('projects.id', $project->id);
            });
        })->where('status_id', $status->id)->get();

        $related_tasks->map(function($task){
            $task->update(['status_id' => Status::where('name', 'pending')->first()->id]);
        });

        $project->statuses()->detach($status->id);
        return apiResponse(null, "status deleted successfully", 200);
    }
}
