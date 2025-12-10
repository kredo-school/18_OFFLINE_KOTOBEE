<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GrammarWrongAnswer extends Model
{
    protected $fillable = [
        'question_id',
        'wrong_order',
        'wrong_sentence',
        'wrong_image_url',
    ];

    public function question() {
        return $this->belongsTo(GrammarQuestion::class, 'question_id');
    }
}
