<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = [
        'classroom_id',
        'title',
        'description',
        'date',
        'start_time',
        'end_time',
    ];

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }
} 