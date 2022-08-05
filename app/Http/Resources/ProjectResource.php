<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
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
        ];
    }
}
