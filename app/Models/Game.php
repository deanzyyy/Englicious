<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;

    protected $fillable = [
        'classroom_id', 'exercise_id', 'name', 'mode', 'type', 'status', 'created_by'
    ];

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    public function exercise()
    {
        return $this->belongsTo(Exercise::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function teams()
    {
        return $this->hasMany(GameTeam::class);
    }

    public function players()
    {
        return $this->hasMany(GamePlayer::class);
    }

    public function scores()
    {
        return $this->hasMany(GameScore::class);
    }
}
