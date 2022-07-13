<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed $rating
 */
class Rating extends Model
{
    use HasFactory;
    protected $fillable = [
        'reviewer_id',
        'reviewed_id',
        'project_id',
        'rating',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
