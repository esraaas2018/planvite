<?php

namespace App\Models;

use App\Scopes\PersonalTaskScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonalTask extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'completed',
        'deadline',
        'user_id',
        'priority'
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected static function booted()
    {
        static::addGlobalScope(new PersonalTaskScope());
    }

}
