<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GamePlayer extends Model
{
    use HasFactory;

    protected $fillable = [
        'game_id', 'team_id', 'user_id', 'student_name'
    ];

    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    public function team()
    {
        return $this->belongsTo(GameTeam::class, 'team_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function score()
    {
        return $this->hasOne(GameScore::class, 'player_id');
    }
}
