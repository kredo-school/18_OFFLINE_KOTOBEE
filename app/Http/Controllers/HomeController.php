<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\GameResult;
use App\Models\VocabQuestion;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // ログイン必須
    }

    ///// 前のindex /////
    // public function index()
    // {
    //     $user = Auth::user();

    //     // Vocabularyゲームの直近5回の結果
    //     $vocabGameId = 2; // VocabularyゲームID
    //     $recentResults = GameResult::where('user_id', $user->id)
    //                         ->where('game_id', $vocabGameId)
    //                         ->orderBy('created_at', 'desc')
    //                         ->take(5)
    //                         ->get();

    //     // ステージ一覧（distinct stage_id）
    //     $stages = VocabQuestion::distinct()->pluck('stage_id');

    //     return view('home', compact('user', 'recentResults', 'stages'));
    // }


    ///// 変更したindex /////
    public function index()
    {
        $user_id = Auth::id();

        // プレイしたステージのid配列
        $played_stage_ids = GameResult::where('game_id', 2)
            ->where('user_id', $user_id)
            ->distinct()
            ->pluck('vcab_stage_id')
            ->values();

        // dd($played_stage_ids);
        
        // 各ステージのurl配列
        $stages = VocabQuestion::where('game_id', 2)
            ->whereRaw('id % 5 = 1')
            ->orderBy('stage_id')
            ->get()
            ->map(fn($s) => route('vocab.start', $s->stage_id))
            ->values()
            ->toArray();

        // dd($stages);
        
        return view(
            'home',
            compact('stages', 'played_stage_ids')
        );
    }

    
}
