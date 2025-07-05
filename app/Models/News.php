<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class News extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'image',
        'description',
        'date',
        'likes',
        'views',
        'created_by',
    ];

    protected $dates = ['date'];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function likedUsers()
    {
        return $this->belongsToMany(User::class, 'news_likes', 'news_id', 'user_id');
    }
} 