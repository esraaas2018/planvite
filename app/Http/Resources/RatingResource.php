<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RatingResource extends JsonResource
{

    public function toArray($request): array
    {
        return [
            'rating' => $this->rating,
            'reviewer_id' => $this->reviewer_id,
            'reviewed_id' => $this->reviewed_id,
            'project_id' => $this->project_id
        ];
    }
}
