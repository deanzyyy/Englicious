<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'category'
    ];

    public function subtopics()
    {
        return $this->hasMany(Subtopic::class);
    }

    public function exercises()
    {
        return $this->hasMany(Exercise::class);
    }

    public function materials()
    {
        return $this->hasMany(Material::class);
    }
} 