<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VocabQuestion extends Model
{
    protected $fillable = [
        'game_id',
        'stage_id',
        'note',
        'word',
        'image_url',
        'part_of_speech',
        'created_by_admin_id',
    ];

    const PARTS_OF_SPEECH = [
        1 => 'Noun',
        2 => 'Verb',
        3 => 'Adjective',
        4 => 'Adverb',
        5 => 'Particle',
        6 => 'Other',
    ];
}

