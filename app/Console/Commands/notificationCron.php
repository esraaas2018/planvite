<?php

namespace App\Console\Commands;

use App\Models\Status;
use App\Models\Task;
use App\Models\User;
use App\Scopes\TaskScope;
use App\Services\NotificationSender;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class notificationCron extends Command
{
    protected $signature = 'notification:cron';


    protected $description = 'Command description';


    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $tasks = Task::withoutGlobalScope(TaskScope::class)->get();
        foreach ($tasks as $task) {
         if($task->sprint->status && $task->status->name != 'done'){
                $deadline = Carbon::parse($task->deadline);
                $created_at = Carbon::parse($task->created_at);
                $diff_deadline =$created_at->diffInDays($deadline);
                $diff_created_at = Carbon::now()->diffInDays($created_at);
                if ($diff_created_at > 0) {
                    $time_percent = ($diff_created_at * 100) /$diff_deadline;
                    if ($time_percent >= 80 && !$task->almost_deadline_notified) {
                        $task->almost_deadline_notified = true;
                        $task->save();
                        NotificationSender::send(
                            $task->assignee, [
                            'title' => 'Deadline is soon!',
                            'body' => 'You have reached 80% of' . $task->name . ' task time.']);
                    }
                    if ($time_percent >= 100 && !$task->deadline_notified) {
                        $task->deadline_notified = true;
                        $task->save();
                        NotificationSender::send(
                            $task->assignee, [
                            'title' => 'You have reached the deadline.',
                            'body' => 'You have reached the deadline of ' . $task->name . ' task.']);
                    }
                }
            }
        }
    }
}
