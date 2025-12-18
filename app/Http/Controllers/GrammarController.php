<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GrammarController extends Controller
{
    public function index()
    {
        $stages = [
            'stage_1' => [
                'わたし は せいと です',
                'わたし は せんせい です',
                'せんせい は ともだち では ありません',
                'わたし は にほんじん では ありません',
                'あなた は にほんじん ですか'
            ],
            'stage_2' => [
                'おとうと は １３さい です',
                'ちち は エンジニア です',
                'はは は がっこう の せんせい です',
                'わたし の かぞく は ６にん です',
                'おねえさん は こうこう ３ねんせい です'
            ],
            'stage_3' => [
                'これ は ペン です',
                'ほん は ５さつ です',
                'これ は わたし の けしゴム です',
                'これ は ふでばこ では ありません',
                'これ は なふだ では ありません'
            ]
        ];

        return view('profile.grammar.index', compact('stages'));
    }
}

