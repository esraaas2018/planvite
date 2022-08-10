<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class ProjectResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id'=>$this->id,
            'name' => $this->name,
            'description' => $this->description,
            'deadline' => $this->deadline,
            'isAdmin' => Auth::id() == $this->user_id,
            'totalDays' =>Carbon::parse(Carbon::parse($this->deadline)->toDateString())->diffInDays(Carbon::parse($this->created_at)->toDateString()) ,
            'daysGone' => min(Carbon::parse(Carbon::parse(today())->toDateString())->diffInDays(Carbon::parse($this->created_at)->toDateString()),Carbon::parse(Carbon::parse($this->deadline)->toDateString())->diffInDays(Carbon::parse($this->created_at)->toDateString())),
            'tasksDoneInProject' => number_format($this->tasksDoneInProject(), 2, '.', ''),
            'tasksDoneInSprint' => number_format($this->tasksDoneInSprint(), 2, '.', ''),
        ];
    }
}
