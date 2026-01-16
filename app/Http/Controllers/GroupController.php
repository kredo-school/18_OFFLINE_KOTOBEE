<?php

namespace App\Http\Controllers;

use App\Models\GameResult;
use Illuminate\Http\Request;
use App\Models\Group;
use App\Models\GroupMember;
use App\Models\Payment;
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
     * グループリスト画面表示
     */
    public function group_list()
    {
        $user = Auth::user();

        //　エラー処理
        abort_unless($user, 404);
        abort_unless(Auth::user()->role === 2, 403);

        $groups = $user->my_groups()
            ->withCount([
                'users as approved_members_count' => function ($q) {
                    $q->where('group_members.status', 2);
                }
            ])
            ->orderBy('created_at', 'asc')
            ->get(['id', 'name', 'note', 'created_at']);
        
        // 全グループ合計
        $current_members = $groups->sum('approved_members_count');

        // 最新の有効な支払い
        $payment = Payment::where('owner_id', $user->id)
            ->where('payment_status', 'COMPLETED')
            ->orderByDesc('paid_at')
            ->first();

        // 最大人数の設定
        if ($payment?->plan_type === 1) {
            $maximum_members = 20;
        } elseif ($payment?->plan_type === 2) {
            $maximum_members = 50;
        } elseif ($payment?->plan_type === 3) {
            $maximum_members = 100;
        } else {
            $maximum_members = null;
        }

        return view('groups.group_list', compact('groups', 'current_members', 'maximum_members', 'payment'));
    }

    /**
     * ダッシュボードから追加のグループを作成する画面表示
    */
    public function create_show()
    {
        return view('groups.create_show');
    }

    /**
     *  ダッシュボードからの追加のグループを保存
    */
    public function new_store(Request $request)
    {
        // ログインしているか
        abort_unless(Auth::check(), 401);

        // グループ管理者か
        abort_unless(Auth::user()->role === 2, 403);

        // バリデーション
        $validated = $request->validate([
            'name'   => ['required', 'string', 'max:12'],
            'secret' => ['required', 'string', 'max:12'],
            'note'   => ['required', 'string', 'max:255'],
        ]);

        // 保存
        $group = Group::create([
            'name'     => $validated['name'],
            'secret'   => $validated['secret'],
            'note'     => $validated['note'] ?? null,
            'owner_id' => Auth::id(),
        ]);

        // 今のグループを切り替える
        session(['current_group_id' => $group->id]);
        
        // ダッシュボードにリダイレクト
        return redirect()
            ->route('group.dashboard', ['group_id' => $group->id])
            ->with('success', 'Group crated.');
    }



    /**
     * グループダッシュボード画面表示
     */
    public function show($id)
    {
        // 現在のグループをsessionに保存
        session(['current_group_id' => $id]);

        /***** カナゲーム用のグラフ作成 *****/
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

        /***** ゲームプレイ回数のデータを作成 *****/
        $group_user_ids = $group->users->pluck('id');

        $game_maps = [
            1 => 'kana',
            2 => 'vocabulary',
            3 => 'grammar',
        ];

        $route_maps = [
            'kana'       => 'group.kana.playcount',
            'vocabulary' => 'group.vocabulary.playcount',
            'grammar'    => 'group.grammar.playcount',
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
            $all_play_counts = $group->users->map(function ($u) use ($counts) {
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

            // $cards[$key] = [
            //     'avg' => $avg_plays,
            //     'top5' => $top5,                
            // ];

            $cards[$key] = [
                'avg' => $avg_plays,
                'top5' => $top5,
                'view_all_url' => route('group.playcount', [
                    'group_id' => $group->id,
                    'game' => $key,
                ]),
            ];
        }

        /***** 各生徒のゲームの進捗度のデータを作成 *****/

        $stage_label = function (?int $stage_id) {
            $stage_id = (int) $stage_id;
            if ($stage_id <= 0) $stage_id = 1;
            $major = intdiv($stage_id - 1, 7) + 1;
            $minor = (($stage_id - 1) % 7) + 1;
            return "{$major}-{$minor}";
        };

        $progress_maps = [
            'vocabulary' => [2, 'vcab_stage_id'],
            'grammar'    => [3, 'gram_stage_id'],
        ];

        $progress_cards = [];

        foreach ($progress_maps as $key => [$game_id, $col]) {

            // 各ユーザの最大stage_idを取得する
            $max_stages = GameResult::query()
                ->whereIn('user_id', $group_user_ids)
                ->where('game_id', $game_id)
                ->whereNotNull($col)
                ->select('user_id', DB::raw("MAX($col) as max_stage"))
                ->groupBy('user_id')
                ->pluck('max_stage', 'user_id');

            $all_progress = $group->users->map(function ($u) use ($max_stages, $stage_label) {

                $max = $max_stages[$u->id] ?? null;

                $next_stage_id = is_null($max) ? 1 : ((int)$max + 1);

                return [
                    'user_id'  => $u->id,
                    'name'     => $u->name,
                    'stage_id' => $next_stage_id,
                    'progress_label' => $stage_label($next_stage_id),
                ];
            });

            // 進捗度top5
            $top5 = $all_progress
                ->sortByDesc('stage_id')
                ->values()
                ->take(5)
                ->map(function ($row, $index) {
                    $row['rank'] = $index + 1;
                    return $row;
                });

            $progress_cards[$key] = [
                'top5' => $top5,
                'view_all_url' => route('group.progress', [ // route
                    'group_id' => $group->id,
                    'game' => $key,
                ]),
            ];
        }

        /***** streakランキング用データを作成 *****/
        $streak_sorted = $group->users
            ->map(function ($u) {
                return [
                    'user_id' => $u->id,
                    'name'    => $u->name,
                    'streak'  => (int) ($u->streak ?? 0),
                ];
            })
            ->sortByDesc('streak')
            ->values();

        // rank付与
        $streak_ranking = $streak_sorted->map(function ($row, $index) {
            $row['rank'] = $index + 1;
            return $row;
        });

        // $streak_ranking を使う
        $streak_card = [
            'top3' => $streak_ranking->take(3), // top3
            'rest' => $streak_ranking->slice(3, 7), // top4~7
            'view_all_url' => route('group.streak', ['group_id' => $group->id]),
        ];

        return view('groups.dashboard', compact(
            'group',
            'avg_scores_60s',
            'avg_time_attacks',
            'cards',
            'progress_cards',
            'streak_card',
        ));
    }

    /** 
     * 全生徒のゲームプレイ回数を表示
     */
    public function playcount(Request $request, $id)
    {

        $group = Group::with([
            'users' => function ($q) {
                $q->wherePivot('status', 2); // グループ参加済みのみ
            }
        ])->findOrFail($id);

        // 全グループのユーザid
        $group_user_ids = $group->users->pluck('id');

        $game_key_to_id = [
            'kana'       => 1,
            'vocabulary' => 2,
            'grammar'    => 3,
        ];

        // View用：プルダウン表示名
        $game_titles = [
            'kana' => 'Kana',
            'vocabulary' => 'Vocabulary',
            'grammar' => 'Grammar',
        ];

        // プルダウン選択値(default: kana)
        $selected_key = $request->query('game', 'kana');
        if (!isset($game_key_to_id[$selected_key])) {
            $selected_key = 'kana';
        }

        $game_id = $game_key_to_id[$selected_key];

        // rank 並び順(default: desc)
        $order = $request->query('order', 'desc');
        $order = in_array($order, ['asc', 'desc'], true) ? $order : 'desc';

        // ユーザーのプレイした人のプレイ回数を集計
        $counts = GameResult::query()
            ->whereIn('user_id', $group_user_ids)
            ->where('game_id', $game_id)
            ->select('user_id', DB::raw('COUNT(*) as play_count'))
            ->groupBy('user_id')
            ->pluck('play_count', 'user_id');

        //　グループ全体に対してプレイ回数を集計(0回を含めるため)
        $all_play_counts = $group->users->map(function ($u) use ($counts) {
            return [
                'user_id' => $u->id,
                'name' => $u->name,
                'play_count' => (int) ($counts[$u->id] ?? 0),
            ];
        });

        // rankクリックでascとdescを切り替え
        $sorted = ($order === 'asc')
            ? $all_play_counts->sortBy('play_count')
            : $all_play_counts->sortByDesc('play_count');

        // 同点時の見た目を安定させたいなら名前で二次ソート（任意）
        // $sorted = $sorted->values()->sort(function ($a, $b) use ($order) {
        //     if ($a['play_count'] === $b['play_count']) {
        //         return strcmp($a['name'], $b['name']);
        //     }
        //     return $order === 'asc'
        //         ? ($a['play_count'] <=> $b['play_count'])
        //         : ($b['play_count'] <=> $a['play_count']);
        // })->values();

        // ランキング用にソートしてrankを付与
        $ranking = $sorted->values()->map(function ($row, $index) {
            $row['rank'] = $index + 1;
            return $row;
        });

        $total_plays = GameResult::query()
            ->whereIn('user_id', $group_user_ids)
            ->where('game_id', $game_id)
            ->count();

        $user_count = max(1, $group_user_ids->count());

        $avg_plays = round($total_plays / $user_count, 2);

        return view('groups.playcount_view_all', [
            'group' => $group,
            'selected_key' => $selected_key, // プルダウンで選ばれたキー
            'game_titles' => $game_titles, // プルダウン用
            'avg_plays' => $avg_plays, // 平均
            'ranking' => $ranking, // ランキング
            'order' => $order, // asc or desc
        ]);
    }

    /**
     * 全生徒のゲーム進捗度を表示
     */
    public function game_progress(Request $request, $id)
    {

        $group = Group::with([
            'users' => function ($q) {
                $q->wherePivot('status', 2);
            }
        ])->findOrFail($id);

        $group_user_ids = $group->users->pluck('id');

        // progrress対象ゲーム(kanaなし)
        $game_key_to_meta = [
            'vocabulary' => [
                'game_id' => 2,
                'col'     => 'vcab_stage_id',
                'title'   => 'Vocabulary',
            ],
            'grammar' => [
                'game_id' => 3,
                'col'     => 'gram_stage_id',
                'title'   => 'Grammar',
            ],
        ];

        // プルダウン選択(default: vocabulary)
        $selected_key = $request->query('game', 'vocabulary');

        if (!isset($game_key_to_meta[$selected_key])) {
            $selected_key = 'vocabulary';
        }

        $game_id = $game_key_to_meta[$selected_key]['game_id'];
        $col     = $game_key_to_meta[$selected_key]['col'];

        // rank並び順(default: desc)
        $order = $request->query('order', 'desc');
        $order = in_array($order, ['asc', 'desc'], true) ? $order : 'desc';

        // ステージラベル生成
        $stage_label = function (?int $stage_id) {
            $stage_id = (int) $stage_id;
            if ($stage_id <= 0) $stage_id = 1;
            $major = intdiv($stage_id - 1, 7) + 1;
            $minor = (($stage_id - 1) % 7) + 1;
            return "{$major}-{$minor}";
        };

        // 各ユーザの最大stage_idを取得
        $max_stages = GameResult::query()
            ->whereIn('user_id', $group_user_ids)
            ->where('game_id', $game_id)
            ->whereNotNull($col)
            ->select('user_id', DB::raw("MAX($col) as max_stage"))
            ->groupBy('user_id')
            ->pluck('max_stage', 'user_id');

        // 未プレイのユーザも含める
        $all_progress = $group->users->map(function ($u) use ($max_stages, $stage_label) {
            $max = $max_stages[$u->id] ?? null;
            $next_stage_id = is_null($max) ? 1 : ((int)$max + 1);

            return [
                'user_id'  => $u->id,
                'name'     => $u->name,
                'stage_id' => $next_stage_id,
                'progress_label' => $stage_label($next_stage_id),
            ];
        });

        // ソート
        $sorted = ($order === 'asc')
            ? $all_progress->sortBy('stage_id')
            : $all_progress->sortByDesc('stage_id');

        // rank付与
        $ranking = $sorted->values()->map(function ($row, $index) {
            $row['rank'] = $index + 1;
            return $row;
        });

        // View用：プルダウン表示名
        $game_titles = [
            'vocabulary' => 'Vocabulary',
            'grammar' => 'Grammar',
        ];

        return view('groups.progress_view_all', [
            'group' => $group,
            'selected_key' => $selected_key,
            'game_titles' => $game_titles,
            'ranking' => $ranking,
            'order' => $order,
        ]);
    }

    /**
     * 全生徒の連続プレイ日数を表示
     */
    public function all_streak(Request $request, $id)
    {

        $group = Group::with([
            'users' => function ($q) {
                $q->wherePivot('status', 2);
            }
        ])->findOrFail($id);

        // rank 並び順(default: desc)
        $order = $request->query('order', 'desc');
        $order = in_array($order, ['asc', 'desc'], true) ? $order : 'desc';

        // streak
        $all = $group->users->map(function ($u) {
            return [
                'user_id' => $u->id,
                'name'    => $u->name,
                'streak'  => (int) ($u->streak ?? 0),
            ];
        });

        // 平均 streak（0含む）
        $user_count = max(1, $all->count());
        $avg_streak = round($all->sum('streak') / $user_count, 2);

        // ソート
        $sorted = ($order === 'asc')
            ? $all->sortBy('streak')
            : $all->sortByDesc('streak');

        // rank付与
        $ranking = $sorted->values()->map(function ($row, $index) {
            $row['rank'] = $index + 1;
            return $row;
        });

        return view('groups.streak_view_all', [
            'group'      => $group,
            'avg_streak' => $avg_streak,
            'ranking'    => $ranking,
            'order'      => $order,
        ]);
    }

    /**
     * 現在のプランの最大人数を取得するprivate関数
     */
    private function max_members_for_owner(int $owner_id): ?int
    {       

        $payment = Payment::where('owner_id', $owner_id)
            ->where('payment_status', 'COMPLETED')
            ->orderByDesc('paid_at')
            ->first();

        // dd($payment?->plan_type);

        if ($payment?->plan_type === 1) {
            return 20;
        } elseif ($payment?->plan_type === 2) {
            return 50;
        } elseif ($payment?->plan_type === 3) {
            return 100;
        } else {
            return null; // custom or no plan
        }

    }

    /**
     * グループ管理者がもつ全グループの人数を取得するprivate関数
     */
    private function current_approved_members_for_owner(int $owner_id): int
    {
        return DB::table('group_members')
            ->join('groups', 'groups.id', 'group_members.group_id')
            ->where([
                ['groups.owner_id', $owner_id],
                ['group_members.status', 2],
            ])
            ->count();
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

        $owner_id = $group->owner_id;

        $max = $this->max_members_for_owner($owner_id);

        // dd($max);

        if ($max !== null) {
            $current = $this->current_approved_members_for_owner($owner_id);

            // すでにプランの上限なら参加不可
            if ($current >= $max) {
                return back()->with('error', "Member limit reached. ({$current}/{$max})");            
            }            
        }

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
        // エラー処理
        abort_unless(Auth::check(), 401);
        abort_unless($group->owner_id === Auth::id(), 403);

        // user_idの配列作成
        $user_ids = array_values(array_filter(explode(',', (string)$request->user_ids)));

        // userが選択されていない時
        if (count($user_ids) === 0) {
            return back()->with('error', 'No users selected.');
        }

        $owner_id = $group->owner_id;

        $max = $this->max_members_for_owner($owner_id);

        if ($max !== null) {

            $current = $this->current_approved_members_for_owner($owner_id);

            // 一人でも超えたら拒否
            if ($current + count($user_ids) > $max) {
                return back()->with(
                    'error',
                    "Member limit would be exceeded. Current: {$current}, Selected: ".count($user_ids).", Max: {$max}."
                );
            }

        }
        
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
    public function edit_show($id)
    {
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
            ->route('group.edit', ['group_id' => $group->id])
            ->with('success', 'Group updated successfully');
    }

    // グループ生徒一覧
    public function students(Group $group)
    {
        $students = $group->users; // belongsToMany 前提
        return view('groups.students', compact('group', 'students'));
    }

    // 生徒削除
    public function removeStudent(Group $group, User $user)
    {
        $group->users()->detach($user->id);
        return redirect()->route('groups.students', $group)
            ->with('success', "$user->name をグループから削除しました。");
    }

    /**
     * 削除確認画面
     */
    public function deleteConfirm(Group $group)
    {
        // 管理者チェック
        if ($group->owner_id !== Auth::id()) {
            abort(403, '権限がありません');
        }

        return view('groups.delete', compact('group'));
    }

    /**
     * 削除処理
     */
    public function destroy(Group $group)
    {
        // Owner check
        if ($group->owner_id !== auth()->id()) {
            abort(403);
        }

        // Delete group and its members
        DB::transaction(function () use ($group) {
            GroupMember::where('group_id', $group->id)->delete();
            $group->delete();
        });

        // Check if the user still owns other groups
        $nextGroup = Group::where('owner_id', auth()->id())
            ->orderBy('created_at', 'asc') // or updated_at / id
            ->first();

        if ($nextGroup) {
            // User still owns another group → redirect to its dashboard
            return redirect()
                ->route('group.dashboard', ['group_id' => $nextGroup->id])
                ->with('success', 'The group has been deleted.');
        }

        // User owns no groups → redirect to Game Select
        return redirect()
            ->route('game.select')
            ->with('success', 'The group has been deleted.');
    }

}
