<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VocabQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'game_id','created_by_admin_id','stage_id','note','image_url','word','part_of_speech'
    ];

    public function game()
    {
        return $this->belongsTo(Game::class);
    }
}

