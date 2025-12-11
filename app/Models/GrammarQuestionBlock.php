<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GrammarQuestionBlock extends Model
{
    protected $fillable = [
        'question_id',
        'block_text',
        'part_of_speech',
        'order_number',
    ];

    public function question() {
        return $this->belongsTo(GrammarQuestion::class, 'question_id');
    }
}
