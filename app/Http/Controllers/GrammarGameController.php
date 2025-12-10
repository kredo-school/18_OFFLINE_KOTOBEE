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
        $stages = GrammarQuestion::where('game_id', 3)->get();
        return view('game.grammar.grammar_stages', compact('stages'));        
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
    public function play($id) 
    {
        return view('game.grammar.play', compact('id'));
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
        
        // // top3抽出
        // $top3 = GameResult::with('user:id,name')
        //     ->where('gram_stage_id', $request->stage_id)
        //     ->whereNotNull('play_time')
        //     ->orderBy('play_time', 'asc')
        //     ->take(3)
        //     ->get();


        return response()->json([
            'saved'       => true,
            'best_time'   => $best_time,
            'rank'        => $rank,
            'top3'        => $top3,
        ]);

    }
}
