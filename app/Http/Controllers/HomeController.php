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

    public function index()
    {
        $user = Auth::user();

        // Vocabularyゲームの直近5回の結果
        $vocabGameId = 2; // VocabularyゲームID
        $recentResults = GameResult::where('user_id', $user->id)
                            ->where('game_id', $vocabGameId)
                            ->orderBy('created_at', 'desc')
                            ->take(5)
                            ->get();

        // ステージ一覧（distinct stage_id）
        $stages = VocabQuestion::distinct()->pluck('stage_id');

        return view('home', compact('user', 'recentResults', 'stages'));
    }
}
