<?php

namespace App\Http\Controllers;

use App\Models\GameResult;
use App\Models\GrammarQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GrammarGameController extends Controller
{ 
    ///// Grammarステージ選択画面 /////
    public function stages()
    {
        $user_id = Auth::id();     
            
        // プレイしたステージのid配列
        $played_stage_ids = GameResult::where('game_id', 3)
            ->where('user_id', $user_id)
            ->distinct()
            ->pluck('gram_stage_id')
            ->values();

        // dd($played_stage_ids);

        // Grammar Questionを取得
        $stages = GrammarQuestion::where('game_id', 3)->get();
            
        return view(
            'game.grammar.grammar_stages',
            compact('stages', 'played_stage_ids')
        );
    }

    public function start_page(Request $request, $stage_id)
    { 
        $user = Auth::user();
        $game_id = 3;

        // ユーザーのベストタイム
        $best_time = GameResult::where('user_id', $user->id)
            ->where('gram_stage_id', $stage_id)
            ->min('play_time');

        // ゲームのtop3を抽出 (ユーザーごとのベストタイム）
        $top3 = GameResult::with('user:id,name')
            ->where('gram_stage_id', $stage_id)
            ->whereHas('user') // ユーザーが存在しないレコードを除外（念のため
            ->select('user_id', DB::raw('MIN(play_time) as best_time'))
            ->groupBy('user_id')
            ->orderBy('best_time', 'asc')
            ->limit(3)
            ->get();
            
        // ゲームタイトル
        $title = 'Grammar Game';

        // 文章
        $description = 'How many seconds can you answer within?';

        // ゲーム遷移url
        $play_url = route('grammar.play', ['stage_id' => $stage_id]);
        
        // 単位
        $unit = 'sec';
        
        if ($request->ajax()) {
            return view('game.game_start_modal', compact(
                'top3', 
                'best_time', 
                'stage_id', 
                'title', 
                'description',
                'unit',
                'play_url'

            ));
        }

        return redirect()->route('grammar.stages');
    }

    ///// JSON API用の関数 //////
    public function start($id)
    {
        $questions = GrammarQuestion::with(['blocks', 'wrong_answers'])
            ->where('stage_id', $id)
            ->get();

        $data = $questions->map(function ($q) {
            return [
                'id'       => $q->id,
                'sentence' => $q->correct_sentence,   // 正解文
                'image'    => $q->image_url,
        
                // 文を構成するブロック
                'blocks' => $q->blocks->map(function ($b) {
                    return [
                        'word' => $b->block_text,
                        'pos'  => $b->part_of_speech,
                    ];
                })->values(),
        
                // ★ question_id ごとの間違い文をまとめる
                'wrong_answers' => $q->wrong_answers->map(function ($w) {
                    return [
                        // "2,1,0,3" → [2,1,0,3] に変換
                        'order'   => array_map('intval', explode(',', $w->wrong_order)),
                        'sentence'=> $w->wrong_sentence,
                        'image'   => $w->wrong_image_url,
                    ];
                })->values(),
            ];
        })->values();



        return response()->json([
            'data' => $data,
            'stage_id' => $id
        ]);
    }

    ///// ゲームプレイ用関数 /////
    public function play($stage_id) 
    {
        return view('game.grammar.play', compact('stage_id'));
    }

    ///// 結果保存 /////
    public function save_result(Request $request) 
    {
        $user = Auth::user();
        $game_id = 3;
        
        // 保存
        GameResult::create([
            'user_id' => $user->id,
            'game_id' => $game_id,
            'gram_stage_id' => $request->stage_id,
            'score' => null,
            'play_time' => $request->play_time         
        ]);


        // ユーザーのベストタイム
        $best_time = GameResult::where('user_id', $user->id)
            ->where('gram_stage_id', $request->stage_id)
            ->min('play_time');
            
        //// ユーザーの順位
        // ★ 全ユーザーの最速タイムを抽出するサブクエリ
        $sub = GameResult::select('user_id', DB::raw('MIN(play_time) as best_time'))
            ->where('gram_stage_id', $request->stage_id)
            ->whereNotNull('play_time')
            ->groupBy('user_id');

        // ★ その最速タイム一覧から順位を算出
        $rank = DB::table(DB::raw("({$sub->toSql()}) as t"))
            ->mergeBindings($sub->getQuery())
            ->where('t.best_time', '<', $best_time)
            ->count() + 1;

        // top3抽出（ユーザーごとのベストタイム）
        $top3 = GameResult::with('user:id,name')
            ->where('gram_stage_id', $request->stage_id)
            ->whereNotNull('play_time')
            ->whereHas('user') // ユーザーが存在しないレコードを除外（念のため）
            ->select('user_id', DB::raw('MIN(play_time) as best_time'))
            ->groupBy('user_id')
            ->orderBy('best_time', 'asc')
            ->take(3)
            ->get();

        return response()->json([
            'saved'       => true,
            'best_time'   => $best_time,
            'rank'        => $rank,
            'top3'        => $top3,
        ]);

    }
}
