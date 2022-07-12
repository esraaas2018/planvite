<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;


class SubTaskResource extends JsonResource
{

    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'task_id'=>$this->task_id,
        ];
    }
}
