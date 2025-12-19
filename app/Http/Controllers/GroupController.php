<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Group;
use Illuminate\Support\Facades\Auth;

class GroupController extends Controller
{
    /**
     * Create Group 画面表示
     */
    public function create()
    {
        return view('groups.create_group');
    }

    /**
     * Create Group の送信処理
     */
    public function store(Request $request)
    {
        $request->validate([
            'plan'   => 'required|in:basic,standard,premium',
            'name'   => 'required|string|max:12',
            'secret' => 'required|string|max:12',
            'note'   => 'nullable|string|max:255',
        ]);

        // 入力内容をセッションに保存（決済成功後に group を作成するため）
        session([
            'group_create_data' => [
                'plan'   => $request->plan,
                'name'   => $request->name,
                'secret' => $request->secret,
                'note'   => $request->note,
            ]
        ]);

        // plan → price の紐づけ
        $price = match ($request->plan) {
            'basic'    => 5.00,
            'standard' => 10.00,
            'premium'  => 20.00,
            default    => 0,
        };

        // すべての値に price を追加して PaymentController に POST で送る
        return redirect()->route('payment.create', $request->all() + ['price' => $price]);
    }
    
    /**
     * グループダッシュボード画面表示
     */
    public function show($id)
    {
        /*** カナゲーム用のグラフ作成 ***/
        $group = Group::with([
            'users.game_results.game_setting'
        ])->findOrFail($id);        
        $subtypes = ['seion', 'dakuon', 'youon'];
        $scripts  = ['hiragana', 'katakana'];


        ///// kana_gameの60sでの各ユーザのベストスコアの平均を算出 /////
        $avg_scores_60s = [];
        foreach ($scripts as $script) {
            foreach ($subtypes as $subtype) {

                // 各ユーザの最大スコアを集める
                $user_max_scores = $group->users->map(function ($user) use ($script, $subtype) {
                    return $user->game_results
                        ->where('game_setting.mode', '60s-count')
                        ->where('game_setting.script', $script)
                        ->where('game_setting.subtype', $subtype)
                        ->whereNotNull('score')
                        ->max('score');
                })->filter(fn($v) => !is_null($v)); // nullを除外                

                // dd($user_max_scores);

                // 最大値の平均を計算
                $avg_scores_60s[$script][$subtype] = $user_max_scores->isEmpty() ? 0 : round($user_max_scores->avg(), 2);
            }
        }
        // dd($avg_scores_60s);


        ///// kana_gameのtime_attackでの各ユーザのベストスコアの平均を算出 /////
        $avg_time_attacks = [];
        foreach ($scripts as $script) {
            foreach ($subtypes as $subtype) {

                // 各ユーザの最速タイムを集める
                $user_max_scores = $group->users->map(function ($user) use ($script, $subtype) {
                    return $user->game_results
                        ->where('game_setting.mode', 'timeattack')
                        ->where('game_setting.script', $script)
                        ->where('game_setting.subtype', $subtype)
                        ->whereNotNull('play_time')
                        ->min('play_time');
                })->filter(fn($v) => !is_null($v)); // nullを除外                

                // dd($user_max_scores);

                // 最速タイムの平均を計算
                $avg_time_attacks[$script][$subtype] = $user_max_scores->isEmpty() ? 0 : round($user_max_scores->avg(), 2);
            }
        }




        return view('groups.dashboard', compact('group', 'avg_scores_60s', 'avg_time_attacks'));
    }
}
