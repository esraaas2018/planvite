<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubTaskDeleteRequest;
use App\Http\Requests\SubTaskIndexRequest;
use App\Http\Requests\SubTaskShowRequest;
use App\Http\Requests\SubTaskStoreRequest;
use App\Http\Requests\SubTaskUpdateRequest;
use App\Http\Resources\SubTaskResource;
use App\Models\SubTask;
use App\Models\Task;

class SubTaskController extends Controller
{

    public function index(SubTaskIndexRequest $request, Task $task)
    {
        $subtasks = $task->subtasks()->get();

        return apiResponse(SubTaskResource::collection($subtasks));
    }

    public function store(SubTaskStoreRequest $request, Task $task)
    {
        $subtask = SubTask::create($request->validated()+
           ['task_id' => $task->id]);
        return apiResponse(new SubTaskResource($subtask), 'subtask created successfully');
    }

    public function update(SubTaskUpdateRequest $request, SubTask $subtask)
    {
        $subtask->update([
            'name' => $request->name,
            'description' => $request->description
        ]);
        return apiResponse(new SubTaskResource($subtask), 'subtask updated successfully');
    }

    public function destroy(SubTaskDeleteRequest $request, SubTask $subtask)
    {
        $subtask->delete();
        return apiResponse(null, 'subtask deleted successfully');
    }
}
