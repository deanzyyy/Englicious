<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subtopic extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'topic_id'
    ];

    public function topic()
    {
        return $this->belongsTo(Topic::class);
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