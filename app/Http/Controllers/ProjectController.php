<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectAddParticipantRequest;
use App\Http\Requests\ProjectDeleteRequest;
use App\Http\Requests\ProjectRevokeParticipantRequest;
use App\Http\Requests\ProjectStoreRequest;
use App\Http\Requests\ProjectUpdateRequest;
use App\Http\Resources\ProjectResource;
use App\Http\Resources\SprintResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\UsersResource;
use App\Models\Project;
use App\Models\Status;
use App\Models\User;
use App\Services\NotificationSender;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{


    public function index()
    {
        $projects = Project::all();
        return apiResponse(ProjectResource::collection($projects));
    }

    public function show(Project $project)
    {
        return apiResponse(SprintResource::collection($project->sprints));
    }

    public function store(ProjectStoreRequest $request)
    {
        $data = [
            'name' => $request->name,
            'deadline' => $request->deadline,
            'description' => $request->description,
            'user_id' => Auth::id()
        ];
        $project = Project::create($data);
        $project->participants()->attach(Auth::user());

        //ordering the statuses and merge the defaults
        $statuses = collect(['pending']);
        $statuses = $statuses->merge((array)$request->statuses);
        $statuses->push('done');

        $statuses->map(function ($status, $key) use ($project) {
            $new_status = Status::firstOrCreate(['name' => $status]);
            $project->statuses()->attach($new_status->id, ['order' => $key]);
        });

        return apiResponse(new ProjectResource($project), 'project create successfully', 201);
    }

    //add user to a project
    public function addUser(ProjectAddParticipantRequest $request, Project $project)
    {
        $user = User::where('email', $request->email)->firstOrFail();
        if ($project->participants()->where('user_id', $user->id)->first()) {
            return apiResponse(new ProjectResource($project), 'the user is already there');
        }
        $project->participants()->attach($user);
        NotificationSender::send(
            $user, [
            'title' => 'Welcome.',
            'body' => 'You have been added to ' . $project->name . ' project']);

        return apiResponse(new ProjectResource($project), 'user added to project successfully');
    }

    //revoke user from a project
    public function revokeUser(ProjectRevokeParticipantRequest $request, Project $project)
    {
        $user = User::where('email', $request->email)->firstOrFail();
        if ($project->participants()->where('user_id', $user->id)->first()) {
            $project->participants()->detach($user);
            $tasks = $project->tasks()->where('user_id', $user->id)->get();
            if ($tasks) {
                $tasks->map(function ($task) {
                    $task->user_id = null;
                    $task->save();
                });
            }
            return apiResponse(new ProjectResource($project), 'user revoked to project successfully');
        }

        return apiResponse(new ProjectResource($project),
            'user not in project '
        );
    }

    public function usersList(Project $project)
    {
         $users = $project->participants()->get();
        return apiResponse(UserResource::collection($users));
    }

    public function update(ProjectUpdateRequest $request, Project $project)
    {
        $project->update($request->validated());
        return apiResponse(new ProjectResource($project));
    }

    public function destroy(ProjectDeleteRequest $request, Project $project)
    {
        $project->delete();
        return apiResponse(null, 'delete successfully', 200);
    }
}
