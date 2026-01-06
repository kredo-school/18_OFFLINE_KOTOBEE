<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GrammarQuestion extends Model
{
    protected $fillable = [
        'game_id',
        'created_by_admin_in',
        'stage_id',
        'note',
        'image_url',
        'correct_sentence',
    ];

    public function blocks() {
        return $this->hasMany(GrammarQuestionBlock::class, 'question_id');
    }

    public function wrong_answers() {
        return $this->hasMany(GrammarWrongAnswer::class, 'question_id');
    }
}
