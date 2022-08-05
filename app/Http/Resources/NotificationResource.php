<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class NotificationResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'title' => $this->title,
            'body' => $this->body,
            'adder' => ($adder = User::where('id', $this->adder_id)->first()) ?
                new UserResource($adder) : null,
            'image' => asset($this->image),
        ];
    }
}

