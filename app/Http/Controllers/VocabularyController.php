<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VocabularyController extends Controller
{
    public function index()
    {
        // 仮データ（後でDBから取得することも可能）
        $stages = [
            'stage1' => ['せんせい', 'せいと', 'わたし', 'ともだち', 'にほん'],
            'stage2' => ['バナナ', 'りんご', 'みかん', 'ぶどう', 'もも']
        ];

        return view('profile.vocabulary.index', compact('stages'));
    }
}
