<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KanaQuestion extends Model
{
    protected $fillable = [
        'kana_char',
        'romaji',
        'kana_type',   // hiragana / katakana
        'sound_type',  // seion / dakuon / youon
    ];
}
