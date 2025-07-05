<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    protected $fillable = [
        'title',
        'description',
        'due_date',
        'file_path',
        'classroom_id',
    ];

    public function classroom()
    {
        return $this->belongsTo(\App\Models\Classroom::class);
    }

    public function submissions()
    {
        return $this->hasMany(\App\Models\AssignmentSubmission::class);
    }
}
