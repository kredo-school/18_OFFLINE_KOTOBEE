<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VocabQuestion extends Model
{
    use HasFactory;

    // 代入可能なカラム
    protected $fillable = [
        'game_id',
        'created_by_admin_id',
        'stage_id',
        'note',
        'image_url',
        'word',
        'part_of_speech',
    ];

    // リレーション設定
    public function game()
    {
        return $this->belongsTo(game::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'created_by_admin_id');
    }
}
