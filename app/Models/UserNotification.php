<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @method static create(array $array)
 */
class UserNotification extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'body',
        'user_id',
        'adder_id',
        'image',
    ];
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
