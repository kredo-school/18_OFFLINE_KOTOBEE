<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GameSetting extends Model
{
    protected $fillable = [
        'game_id',
        'mode',
        'oder_type',
        'script',
        'subtype',
    ];

}
