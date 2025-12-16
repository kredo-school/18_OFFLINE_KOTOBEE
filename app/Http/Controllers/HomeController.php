<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\GameResult;
use App\Models\VocabQuestion;
use Illuminate\Support\Facades\DB;

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
            ->map(fn($s) => route('vocab.start_page', $s->stage_id))
            ->values()
            ->toArray();

        // dd($stages);
        
        return view(
            'home',
            compact('stages', 'played_stage_ids')
        );
    }

    ///// ゲームスタートmodal /////
    public function start_page(Request $request, $stage_id) 
    {
        $user = Auth::user();
        $game_id = 2;

        // ユーザーのベストタイム
        $best_value = GameResult::where('user_id', $user->id)
            ->where('vcab_stage_id', $stage_id)
            ->min('play_time');
        
        // ゲームのtop3を抽出 (ユーザーごとのベストタイム)
        $top3 = GameResult::with('user:id,name')
            ->where('vcab_stage_id', $stage_id)
            ->whereHas('user') // ユーザーが存在しないレコードを除外 (念の為)
            ->select('user_id', DB::raw('MIN(play_time) as best_value'))
            ->groupBy('user_id')
            ->orderBy('best_value', 'asc')
            ->limit(3)
            ->get();

        // ゲームタイトル
        $title = 'Vocabulary Game';

        // 文章
        $description = 'How many seconds can you answer within?';

        // ゲーム遷移url
        $play_url = route('vocab.start', $stage_id);

        // 単位
        $unit = 'sec';

        return view('game.game_start_modal', compact(
            'top3', 
            'best_value', 
            'stage_id', 
            'title', 
            'description',
            'unit',
            'play_url'

        ));
        
        return redirect()->route('home');
    }
    
}
