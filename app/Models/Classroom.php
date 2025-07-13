<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Classroom extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'password',
        'teacher_id',
        'password_changed_at',
    ];

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'classroom_user')
                    ->where('role', 'student')
                    ->withTimestamps();
    }

    public function exercises()
    {
        return $this->belongsToMany(Exercise::class, 'classroom_exercise')
                    ->withTimestamps();
    }

    public function materials()
    {
        return $this->belongsToMany(\App\Models\Material::class, 'classroom_material')
                    ->withTimestamps();
    }

    public function assignments()
    {
        return $this->hasMany(\App\Models\Assignment::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function schedules()
    {
        return $this->hasMany(\App\Models\Schedule::class);
    }
}
