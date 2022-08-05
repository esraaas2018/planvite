<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'title' => $this->title,
            'body' => $this->body,
            'adder' => new UserResource(User::where('id', $this->adder_id)->findOrFail()) ?? ''
        ];
    }
}

