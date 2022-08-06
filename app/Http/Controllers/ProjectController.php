<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectAddParticipantRequest;
use App\Http\Requests\ProjectDeleteRequest;
use App\Http\Requests\ProjectRevokeParticipantRequest;
use App\Http\Requests\ProjectStoreRequest;
use App\Http\Requests\ProjectUpdateRequest;
use App\Http\Resources\ProjectDetailsResource;
use App\Http\Resources\ProjectResource;
use App\Http\Resources\SprintResource;
use App\Http\Resources\UserResource;
use App\Models\Project;
use App\Models\Status;
use App\Models\User;
use App\Services\NotificationSender;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{


    public function index()
    {
        $projects = Project::all();
        return apiResponse(ProjectResource::collection($projects));
    }

    public function UserProjects()
    {
        $user = Auth::user();
        $projects = $user->own_projects()->get();
        return apiResponse(ProjectResource::collection($projects));
    }

//    public function projectDetails()
//    {
//        $user = Auth::user();
//        $projects = Project::all();
//        $all_tasks_count = $user->assigned_tasks()->count();
//        $done_task_count = $user->assigned_tasks()->whereHas('status', function (Builder $query) {
//            $query->where('name', 'done');
//        })->get()->count();
//        $all_tasks_count > 0 ? $done_task_percent = ($done_task_count * 100) / $all_tasks_count : $done_task_percent = 0;
//        $new = $projects->concat(['done_task_count' => $done_task_percent]);
//        $new->map(function ($project) {
//
//            $task_count = $project->tasks()->count();
//            $done_task_count = $project->tasks()->whereHas('status', function (Builder $query) {
//                $query->where('name', 'done');
//            })->get()->count();
//            $pending_task_count = $project->tasks()->whereHas('status', function (Builder $query) {
//                $query->where('name', 'pending');
//            })->get()->count();
//            $task_count > 0 ? $done_percent = ($done_task_count * 100) / $task_count : $done_percent = 0;
//            $task_count > 0 ? $pending_percent = ($pending_task_count * 100) / $task_count : $pending_percent = 0;
//            $project->done_percent = $done_percent;
//            $project->pending_percent = $pending_percent;
//
//        });
//        return apiResponse(ProjectDetailsResource::collection($new));;
//
//    }

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


        $pending = Status::firstOrCreate(['name' => 'pending']);
        $project->statuses()->attach($pending->id, ['order' => 0]);

        $pending = Status::firstOrCreate(['name' => 'done']);
        $project->statuses()->attach($pending->id, ['order' => 10e5]);

        return apiResponse(new ProjectResource($project), 'project create successfully', 201);
    }

    //add user to a project
    public function addUser(ProjectAddParticipantRequest $request, Project $project)
    {
        $user = User::where('email', $request->email)->firstOrFail();


        if ($project->participants()->where('user_id', $user->id)->first()) {
            return apiResponse(new ProjectResource($project), 'the user is already there', 422);
        }
        $project->participants()->attach($user);
        NotificationSender::send(
            $user,
            [
                'title' => 'Welcome.',
                'body' => 'You have been added to ' . $project->name . ' project'
            ],
            $project->user_id,
            $user->image
        );

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
