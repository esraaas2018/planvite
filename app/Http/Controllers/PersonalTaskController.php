<?php

namespace App\Http\Controllers;

use App\Http\Requests\PersonalTaskDestroyRequest;
use App\Http\Requests\PersonalTaskShowRequest;
use App\Http\Requests\PersonalTaskStoreRequest;
use App\Http\Requests\PersonalTaskUpdateRequest;
use App\Http\Resources\PersonalTaskResource;
use App\Models\PersonalTask;
use Illuminate\Support\Facades\Auth;
class PersonalTaskController extends Controller
{

    public function index()
    {
        $user =Auth::user();
     return apiResponse($user->personal_tasks()->get());
    }

    public function store(PersonalTaskStoreRequest $request)
    {
        $newTask = PersonalTask::create(
            $request->validated() + [
                'user_id' => Auth::id(),
                'completed' => $request->has('completed'),
            ]);
        return apiResponse($newTask, 'task created.');
    }

    public function show(PersonalTaskShowRequest $request,PersonalTask $personal_task)
    {
        return apiResponse(PersonalTaskResource::make($personal_task));
    }

    public function update(PersonalTask $personal_task,PersonalTaskUpdateRequest $request)
    {
        $personal_task->update($request->all());
        return apiResponse(PersonalTaskResource::make($personal_task),'task updated.');
    }

    public function destroy(PersonalTask $personal_task,PersonalTaskDestroyRequest $request)
    {
            $personal_task->delete();
        return apiResponse(null,'task deleted');
    }
}
