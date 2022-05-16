<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'deadline',
        'sprint_id',
        'user_id',
        'status',
        'agenda_id'
    ];
    public function sprint()
    {
        return $this->belongsTo(Sprint::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class,);
    }
    public function agenda()
    {
        return $this->belongsTo(Agenda::class);
    }
}
