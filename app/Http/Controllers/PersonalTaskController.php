<?php

namespace App\Http\Controllers;

use App\Http\Requests\PersonalTaskDestroyRequest;
use App\Http\Requests\PersonalTaskStoreRequest;
use App\Http\Requests\PersonalTaskUpdateRequest;
use App\Http\Requests\PesronalTaskChangeStatusRequest;
use App\Http\Resources\PersonalTaskResource;
use App\Models\PersonalTask;
use Illuminate\Support\Facades\Auth;

class PersonalTaskController extends Controller
{

    public function index()
    {
        $personal_tasks = PersonalTask::all();
        return apiResponse(PersonalTaskResource::collection($personal_tasks), 'all personal tasks');
    }

    public function store(PersonalTaskStoreRequest $request)
    {
        $newTask = PersonalTask::create(
            $request->validated() + [
                'user_id' => Auth::id(),
                'completed' => $request->has('completed'),
            ]);
        return apiResponse(new PersonalTaskResource($newTask), 'task created.');
    }

    public function show(PersonalTask $personal_task)
    {
        return apiResponse(new PersonalTaskResource($personal_task));
    }

    public function update(PersonalTask $personal_task, PersonalTaskUpdateRequest $request)
    {
        $personal_task->update($request->all());
        return apiResponse(new PersonalTaskResource($personal_task), 'task updated.');
    }

    public function destroy(PersonalTask $personal_task, PersonalTaskDestroyRequest $request)
    {
        $personal_task->delete();
        return apiResponse(null, 'task deleted');
    }

    public function changeStatus(PesronalTaskChangeStatusRequest $request, PersonalTask $personal_task)
    {
        $personal_task->completed =$request->completed;
        $personal_task->save();
        return apiResponse(new PersonalTaskResource($personal_task), 'task changed Status successfully');
    }

    public function completedTasks()
    {
        $completed_count = PersonalTask::where('completed', 1)->get()->count();
        $personal_tasks_count = PersonalTask::all()->count();
        return ($personal_tasks_count==0)?0: $completed_count/$personal_tasks_count;
    }
}
