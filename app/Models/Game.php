<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;

    protected $fillable = ['name','description','game_type'];

    public function vocabQuestions()
    {
        return $this->hasMany(VocabQuestion::class);
    }

    public function gameResults()
    {
        return $this->hasMany(GameResult::class);
    }
}
