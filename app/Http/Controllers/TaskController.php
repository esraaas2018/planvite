<?php

namespace App\Http\Controllers;

use App\Http\Requests\AgendaRequest;
use App\Http\Requests\TaskChangeStatusRequest;
use App\Http\Requests\TaskDeleteRequest;
use App\Http\Requests\TaskIndexRequest;
use App\Http\Requests\TaskStoreRequest;
use App\Http\Requests\TaskUpdateRequest;
use App\Http\Resources\TaskResource;
use App\Models\Project;
use App\Models\Sprint;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index(TaskIndexRequest $request, Project $project)
    {
//        TaskScope applied
//        $tasks = $project->sprints()->where('status', true)->firstOrFail()
//            ->tasks()->whereHas('status', function ($q) use ($request) {
//                return $q->where('id', $request->status_id);
//            })->get();

        $statuses = $project->statuses()->get();

        $data = $statuses->map(function ($status) use ($project) {
            return [
                'status_name' => $status->name,
                'tasks' => TaskResource::collection($project->sprints()->where('status', true)->firstOrFail()
                    ->tasks()->whereHas('status', function ($q) use ($status) {
                        return $q->where('id', $status->id);
                    })->get())
            ];
        });

        return apiResponse($data);
    }

    public function store(Sprint $sprint, TaskStoreRequest $request)
    {
        $task = Task::create($request->validated() + [
                'status_id' => 1,
                'sprint_id' => $sprint->id
            ]);
        return apiResponse(new TaskResource($task), 'task created successfully', 201);
    }

    public function show(Task $task)
    {
        return apiResponse(new TaskResource($task));
    }

    public function update(TaskUpdateRequest $request, Task $task)
    {
        $task->update([
            'name' => $request->name,
            'deadline' => $request->deadline,
            'description' => $request->description
        ]);
        return apiResponse(new TaskResource($task), 'task updated successfully');
    }

    public function changeStatus(TaskChangeStatusRequest $request, Task $task)
    {
//        dd($request->all());
        $task->status_id = $request->status_id;
        $task->save();
        return apiResponse(new TaskResource($task), 'task changed Status successfully');
    }

    public function destroy(TaskDeleteRequest $request, Task $task)
    {
        $task->delete();
        return apiResponse(null, 'task deleted successfully');
    }

    public function pinTask(AgendaRequest $request, Task $task)
    {
        $user = Auth::user();

        if ($user->pinnedTasks()->where('task_id', $task->id)->exists()) {
            $user->pinnedTasks()->detach($task);
            return apiResponse(null, 'task unpinned');
        }

        $user->pinnedTasks()->attach($task);
        return apiResponse(null, 'task pinned');
    }

}
