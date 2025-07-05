<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameTeam extends Model
{
    use HasFactory;

    protected $fillable = [
        'game_id', 'name'
    ];

    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    public function players()
    {
        return $this->hasMany(GamePlayer::class, 'team_id');
    }
}
