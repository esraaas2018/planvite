<?php

namespace App\Http\Controllers;

use App\Http\Requests\SprintDeleteRequest;
use App\Http\Requests\SprintOffRequest;
use App\Http\Requests\SprintRecycleRequest;
use App\Http\Requests\SprintRunRequest;
use App\Http\Requests\SprintShowRequest;
use App\Http\Requests\SprintStoreRequest;
use App\Http\Requests\SprintUpdateRequest;
use App\Http\Resources\SprintResource;
use App\Models\Project;
use App\Models\Sprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SprintController extends Controller
{
    public function index(Project $project)
    {
        $sprints = $project->sprints()->get();
        return apiResponse(SprintResource::collection($sprints));
    }

    public function store(Project $project,SprintStoreRequest $request )
    {
        $sprint = Sprint::create($request->validated()+ [
            'project_id'=>$project->id,'status'=>false
            ]);
        return apiResponse(new SprintResource($sprint),'sprint created successfully',201);
    }

    public function show(SprintShowRequest $request, Sprint $sprint)
    {
       return apiResponse(new SprintResource($sprint));
    }

    public function update(SprintUpdateRequest $request, Project $project,Sprint $sprint)
    {
        $sprint->update($request->validated());
        return apiResponse(new SprintResource($sprint),'sprint updated successfully');

    }

    public function destroy(SprintDeleteRequest $request,Project $project,Sprint $sprint)
    {
        $sprint->delete();
        return apiResponse(null,'sprint deleted successfully');
    }

    public function runSprint(SprintRunRequest $request ,Sprint $sprint)
    {
        $sprints =$sprint->project->sprints()->where('status',true)->first();
        if($sprints){
            return response()->json('you can not turn on');
        }
        $sprint->status = true;
        $sprint->save();
        return apiResponse(new SprintResource($sprint),'sprint turn on successfully');
    }

    public function offSprint(SprintOffRequest $request ,Sprint $sprint)
    {
        $sprint->status = false;
        $sprint->save();
        return apiResponse(new SprintResource($sprint),'sprint turn off successfully');
    }

    public function recycleSprint(SprintRecycleRequest $request ,Sprint $sprint)
    {
        $sprint->tasks()->get()->map(function($task){
            $task->status_id = 1;
            $task->save();
        });
        return apiResponse(new SprintResource($sprint),'sprint recycle successfully');
    }

}
