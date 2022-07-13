<?php

use App\Http\Controllers\PersonalTaskController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\SprintController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\SubTaskController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('sendNotificationToUser', [UserController::class, 'sendNotificationToUser']);
Route::middleware(['treblle'])->group(function () {
    // YOUR API ROUTES GO HERE
//    Route::post('/register', [UserController::class, 'register']);
//    Route::post('/login', [UserController::class, 'login']);
//
//    Route::group(['middleware' => 'auth:sanctum'], function () {
//
//        Route::get('/logout', [UserController::class, 'logout']);
//        //project
//        Route::apiResource('projects', "ProjectController");
//        Route::post('/projects/{project}/addParticipant', [ProjectController::class ,'addUser' ]);
//        Route::post('/projects/{project}/revokeParticipant', [ProjectController::class ,'revokeUser' ]);
//        //sprint
//        Route::apiResource('sprints', "SprintController")->except(['store','index']);
//        Route::post('/projects/{project}/sprints', [SprintController::class, 'store']);
//        Route::get('/projects/{project}/sprints', [SprintController::class, 'index']);
//        Route::post('/sprints/{sprint}/runSprint', [SprintController::class, 'runSprint']);
//        Route::post('/sprints/{sprint}/offSprint', [SprintController::class, 'offSprint']);
//        Route::post('/sprints/{sprint}/recycle', [SprintController::class, 'recycleSprint']);
//        //task
//        Route::apiResource('tasks', "TaskController")->except(['store', 'index']);
//        //board
//        Route::get('/projects/{project}/tasks',  [TaskController::class, 'index']);
//        Route::put('/tasks/{task}/change-status',  [TaskController::class, 'changeStatus']);
//        Route::post('/sprints/{sprint}/tasks',  [TaskController::class, 'store']);
//
//        Route::get('/projects/{project}/statuses',  [StatusController::class, 'index']);
//
//        Route::put('/tasks/{task}/pin',  [TaskController::class, 'pinTask']);
//        Route::apiResource('personal_tasks', "PersonalTaskController");
//        Route::put('/personal_tasks/{personal_task}/change-status',[PersonalTaskController::class ,'changeStatus']);
//
////subtask
//        Route::apiResource('subtasks', "SubTaskController")->except(['index','store']);
//        Route::get('tasks/{task}/subtasks',  [SubTaskController::class, 'index']);
//        Route::post('tasks/{task}/subtasks',  [SubTaskController::class, 'store']);
//
//
//    });
});

Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);

Route::group(['middleware' => 'auth:sanctum'], function () {

    Route::get('/check-token', [UserController::class, 'checkToken']);
    Route::get('/logout', [UserController::class, 'logout']);

    Route::post('/settings', [UserController::class, 'settings']);

    //project
    Route::apiResource('projects', "ProjectController");
    Route::post('/projects/{project}/addParticipant', [ProjectController::class, 'addUser']);
    Route::post('/projects/{project}/revokeParticipant', [ProjectController::class, 'revokeUser']);
    //sprint
    Route::apiResource('sprints', "SprintController")->except(['store', 'index']);
    Route::post('/projects/{project}/sprints', [SprintController::class, 'store']);
    Route::get('/projects/{project}/sprints', [SprintController::class, 'index']);
    Route::post('/sprints/{sprint}/runSprint', [SprintController::class, 'runSprint']);
    Route::post('/sprints/{sprint}/offSprint', [SprintController::class, 'offSprint']);
    Route::post('/sprints/{sprint}/recycle', [SprintController::class, 'recycleSprint']);
    //task
    Route::apiResource('tasks', "TaskController")->except(['store', 'index']);
    //board
    Route::get('/projects/{project}/tasks', [TaskController::class, 'index']);
    Route::put('/tasks/{task}/change-status', [TaskController::class, 'changeStatus']);
    Route::post('/sprints/{sprint}/tasks', [TaskController::class, 'store']);

    Route::get('/projects/{project}/statuses', [StatusController::class, 'index']);

    Route::put('/tasks/{task}/pin', [TaskController::class, 'pinTask']);
    Route::apiResource('personal_tasks', "PersonalTaskController");
    Route::put('/personal_tasks/{personal_task}/change-status', [PersonalTaskController::class, 'changeStatus']);

    //subtask
    Route::apiResource('subtasks', "SubTaskController")->except(['index', 'store']);
    Route::get('tasks/{task}/subtasks', [SubTaskController::class, 'index']);
    Route::post('tasks/{task}/subtasks', [SubTaskController::class, 'store']);


    //list of all users in a project
    Route::get('/projects/{project}/users', [ProjectController::class, 'usersList']);

    //Rating
    Route::post('/projects/{project}/{reviewed}', [RatingController::class, 'rateUser']);
});
Route::get('ttt', function(){
    return User::find(5)->rating;
});
Route::get('/test', function (Request $request) {
    //$project = Project::first();

    $user = User::orderByDesc('id')->first();
    return $user->createToken('test')->plainTextToken;
});

Route::get('/test2', function (Request $request) {
    //$project = Project::first();
    //$user = Auth::user();
    //return $user->createToken('test')->plainTextToken;
    dd(Task::all()->pluck('id')->toArray());
})->middleware('auth:sanctum');
