<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GameResult extends Model
{
    protected $fillable = [
        'user_id',
        'game_id',
        'setting_id',
        'created_by_admin_id',
        'score',
        'play_time',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
