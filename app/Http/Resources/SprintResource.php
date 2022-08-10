<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SprintResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $tasks = $this->tasks();
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'deadline' => $this->deadline,
            'project_id' => $this->project_id,
            'tasks' => $request->of_user ? TaskResource::collection($tasks->ofUser()->get()) : TaskResource::collection($tasks->get()),
            'isActive' => $this->status
        ];
    }
}
