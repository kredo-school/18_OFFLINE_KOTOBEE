<?php

namespace App\Http\Controllers;

use App\Models\GameResult;
use Illuminate\Http\Request;
use App\Models\Group;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
            'users' => function ($q) {
                $q->wherePivot('status', 2);
            },
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

        // グループのユーザ
        $group_user_ids = $group->users->pluck('id');

        $game_maps = [
            1 => 'kana',     // Kana play count
            2 => 'word',     // Word play count
            3 => 'grammar',  // Grammar play count            
        ];

        $cards = [];

        foreach ($game_maps as $game_id => $key) {

            // ユーザーのプレイした人のプレイ回数を集計
            $counts = GameResult::query()
                ->whereIn('user_id', $group_user_ids)
                ->where('game_id', $game_id)
                ->select('user_id', DB::raw('COUNT(*) as play_count'))
                ->groupBy('user_id')
                ->pluck('play_count', 'user_id');
            
            //　グループ全体に対してプレイ回数を集計(0回を含めるため)
            $all_play_counts = $group->users->map(function ($u) use ($counts){
                return [
                    'user_id' => $u->id,
                    'name' => $u->name,
                    'play_count' => (int) ($counts[$u->id] ?? 0),
                ];
            });
                
            // dd($counts);

            // top5を取得
            $top5 = $all_play_counts
                ->sortByDesc('play_count')
                ->values()
                ->take(5)
                ->map(function ($row, $index) {
                    $row['rank'] = $index + 1;
                    return $row;
                });
            
            // dd($top5);

            // 全生徒の総プレイ数
            $total_plays = GameResult::query()
                ->whereIn('user_id', $group_user_ids)
                ->where('game_id', $game_id)
                ->count();
                
            $user_count = max(1, $group_user_ids->count());

            // dd($user_count);

            $avg_plays = round($total_plays / $user_count, 2);

            $cards[$key] = [
                'avg' => $avg_plays,
                'top5' => $top5,                
            ];
            
            // $cards[$key] = [
            //     'avg' => $avg_plays,
            //     'top5' => $top5,
            //     'view_all_url' => route('groups.playcounts.index', [
            //         'group' => $group->id,
            //         'game_id' => $game_id,
            //     ]),
            // ]; 
        }

        return view('groups.dashboard', compact(
            'group',
            'avg_scores_60s',
            'avg_time_attacks',
            'cards'
        ));

    }

    /**
     * 参加申請者表示画面
     */
    public function applicants_show($id)
    {
        $group = Group::findOrFail($id);       
        
        $applicants = $group->users()
            ->orderByPivot('status', 'asc')
            ->orderByPivot('created_at', 'asc')
            ->get();

        return view('groups.applicants', compact('group', 'applicants'));
    }

    /**
     * 参加申請処理
     */
    public function applicant_approval(Group $group, User $user)
    {        
        // グループadminが操作しているか
        abort_unless($group->owner_id === Auth::id(), 403);

        DB::table('group_members')
            ->where('group_id', $group->id)
            ->where('user_id', $user->id)
            ->update(['status' => 2]);

        return back()->with('success', 'Approved.');
    }

    /**
     * 参加拒否処理
     */
    public function applicant_deny(Group $group, User $user)
    {
        // グループadminが操作しているか
        abort_unless($group->owner_id === Auth::id(), 403);

        DB::table('group_members')
            ->where('group_id', $group->id)
            ->where('user_id', $user->id)
            ->delete();
        
        return back()->with('success', 'Denied.');
    }

    /**
     * 参加申請処理(複数)
     */
    public function applicant_bulk_approval(Request $request, Group $group)
    {        
        abort_unless(Auth::check(), 401);
        abort_unless($group->owner_id === Auth::id(), 403);

        // user_idの配列作成
        $user_ids = explode(',', $request->user_ids);

        DB::table('group_members')
            ->where('group_id', $group->id)
            ->whereIn('user_id', $user_ids)
            ->update(['status' => 2]);
            
        return back()->with('success', 'Selected users approved.');
    }

    /**
     * 参加拒否処理(複数)
     */
    public function applicant_bulk_deny(Request $request, Group $group)
    {
        abort_unless(Auth::check(), 401);
        abort_unless($group->owner_id === Auth::id(), 403);
        
        // user_idの配列作成
        $user_ids = explode(',', $request->user_ids);

        DB::table('group_members')
            ->where('group_id', $group->id)
            ->whereIn('user_id', $user_ids)
            ->delete();
            
        return back()->with('success', 'Selected users denied.');
    }

    /**
     * グループ編集
     */
    public function edit_show($id) {
        $group = Group::findOrFail($id);
        return view('groups.edit', compact('group'));
    }

    /**
     * グループ編集の処理
     */
    public function edit_process(Request $request, $id)
    {
        $group = Group::findOrFail($id);

        $group->name = $request->input('name');
        $group->secret = $request->input('secret');
        $group->note = $request->input('note');

        $group->save();

        return redirect()
            ->route('group.edit', ['id' => $group->id])
            ->with('success', 'Group updated successfully');
    }

}
