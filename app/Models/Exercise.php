<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Exercise extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'category',
        'topic_id',
        'subtopic_id',
        'file_path',
        'is_file_upload',
        'created_by',
        'duration',
    ];

    protected $casts = [
        'is_file_upload' => 'boolean'
    ];

    protected $with = ['topic', 'subtopic'];

    protected $withCount = ['questions'];

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

    public function subtopic()
    {
        return $this->belongsTo(Subtopic::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function classrooms()
    {
        return $this->belongsToMany(Classroom::class, 'classroom_exercise')
                    ->withTimestamps();
    }

    public function submissions()
    {
        return $this->hasMany(StudentSubmission::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
