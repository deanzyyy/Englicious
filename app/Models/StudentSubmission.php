<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentSubmission extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'exercise_id',
        'classroom_id',
        'answers',
        'score',
        'is_completed',
        'essay_scores',
        'essay_comments'
    ];

    protected $casts = [
        'answers' => 'array',
        'is_completed' => 'boolean',
        'score' => 'decimal:2',
        'essay_scores' => 'array',
        'essay_comments' => 'array'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function exercise()
    {
        return $this->belongsTo(Exercise::class);
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }
} 