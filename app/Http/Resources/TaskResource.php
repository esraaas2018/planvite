<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'deadline' => $this->deadline,
            'sprint_id' => $this->sprint_id,
            'user_id' => $this->user_id,
            'status_id' => $this->status_id,
            'priority' => $this->priority,
            'isAdmin' => Auth::id() == $this->project->user_id,
            'isMyTask' => Auth::id() == $this->user_id,
            'assignee info' => ($assignee = User::where('id', $this->user_id)->first()) ?
                new UserResource($assignee) : null,
            'subtasks list' => SubTaskResource::collection($this->subtasks()->get()),
        ];
    }
}
