<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'exercise_id',
        'question_text',
        'options',
        'correct_answer',
        'image_path',
        'audio_path',
        'type'
    ];

    protected $casts = [
        'options' => 'array'
    ];

    public function exercise()
    {
        return $this->belongsTo(Exercise::class);
    }
}
