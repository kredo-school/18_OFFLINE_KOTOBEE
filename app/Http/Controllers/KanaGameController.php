<?php

namespace App\Http\Controllers;

use App\Models\GameSetting;
use App\Models\KanaQuestion;
use App\Models\GameResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class KanaGameController extends Controller
{
    /**
     * ① 設定一覧画面
     */
    public function options()
    {
        $settings = GameSetting::where('game_id', 1)->get(); // game_id は必要に応じて変更        

        return view('game.kana_options', compact('settings'));
    }

    ///// ゲームスタートmodal /////
    public function start_page(Request $request, $setting_id)
    {
        // ゲーム設定情報取り出し、ない場合404エラー
        $game_setting = GameSetting::findOrFail($setting_id);

        $user = Auth::user();
        $game_id = 1;
        $mode = $game_setting->mode;
    
        // modeごとの設定
        if ($mode === '60s-count') {
            $order_column = 'score';
            $order_direction = 'desc';
            $description = 'How many can you get in 60s?';
            $unit = 'こ';

            $top3 = GameResult::with('user')
                ->select('user_id', DB::raw("MAX($order_column) as best_value"))
                ->where('game_id', $game_id)
                ->where('setting_id', $setting_id)
                ->groupBy('user_id')
                ->orderBy('best_value', $order_direction)
                ->limit(3)
                ->get();

            $best_value = GameResult::where('user_id', $user->id)
                ->where('game_id', $game_id)
                ->where('setting_id', $setting_id)
                ->max($order_column);

        } elseif ($mode === 'timeattack') {
            $order_column = 'play_time';
            $order_direction = 'asc';
            $description = 'How many seconds does it take to get everything?';
            $unit = 'sec';

            $top3 = GameResult::with('user')
                ->select('user_id', DB::raw("MIN($order_column) as best_value"))
                ->where('game_id', $game_id)
                ->where('setting_id', $setting_id)
                ->groupBy('user_id')
                ->orderBy('best_value', $order_direction)
                ->limit(3)
                ->get();

            $best_value = GameResult::where('user_id', $user->id)
                ->where('game_id', $game_id)
                ->where('setting_id', $setting_id)
                ->min($order_column);            
        }

        // ゲームタイトル
        $title = 'Kana Game';

        // ゲーム開始url
        $play_url = route('kana.start', $setting_id);

        // modal出力
        if ($request->ajax()) {
            return view('game.game_start_modal', compact(
                'top3',
                'best_value',
                'title',
                'description',
                'unit',
                'play_url'
            ));
        }
    
        return redirect()->route('kana.options');
    }    

    /**
     * ② 選択された設定でゲーム開始
     */
    public function start($id)
    {
        $setting = GameSetting::findOrFail($id);

        // ここで questions を生成（仮のサンプル）
        $questions = collect($this->makeQuestions($setting->script, $setting->subtype));

        return view('game.kana.kana_game', [
            'mode'        => $setting->mode,
            'order_type'  => $setting->order_type,
            'sound_type'  => $setting->subtype,
            'questions'   => $questions,
            'setting_id'  => $setting->id,  // ← 追加！ 12/1
        ]);
    }


    /**
     * ③ 設問生成
     */
    private function makeQuestions($script, $subtype)
    {
        // ▼ script → DB の kana_type
        $kana_type = match ($script) {
            'hiragana' => 1,
            'katakana' => 2,
            default => 1,
        };

        // ▼ subtype → DB の sound_type
        $sound_type = match ($subtype) {
            'seion' => 1,
            'dakuon' => 2,
            'youon' => 3,
            default => 1,
        };

        // ▼ DB から該当の questions を取得（ID順）
        $questions = DB::table('Kana_questions')
            ->where('kana_type', $kana_type)
            ->where('sound_type', $sound_type)
            ->orderBy('id')
            ->get()
            ->toArray(); // array_spliceを使うので配列に変換

        // ▼ 清音（seion）のみ、50音表の空欄補正を実施
        if ($sound_type === 1) {
            // や行補正（や・?・ゆ・?・よ）
            array_splice($questions, 36, 0, [(object)['kana_char' => '', 'romaji' => '']]);
            array_splice($questions, 38, 0, [(object)['kana_char' => '', 'romaji' => '']]);

            // わ行補正（わ・?・を・?・ん）
            array_splice($questions, 46, 0, [(object)['kana_char' => '', 'romaji' => '']]);
            array_splice($questions, 48, 0, [(object)['kana_char' => '', 'romaji' => '']]);
        }
    
        // 拗音の空欄補正（3列 → 5列）
        if ($sound_type === 3) {

            // 空白オブジェクト
            $blank = (object)['kana_char' => '', 'romaji' => ''];

            // 拗音は 11 行 × 3 列
            // 1行につき： [1文字, 空白, 1文字, 空白, 1文字] を作る
            $newQuestions = [];
            $index = 0;

            for ($i = 0; $i < 11; $i++) {

                // 1列目
                $newQuestions[] = $questions[$index];
                // 2列目（空白）
                $newQuestions[] = $blank;

                // 3列目
                $newQuestions[] = $questions[$index + 1];
                // 4列目（空白）
                $newQuestions[] = $blank;

                // 5列目
                $newQuestions[] = $questions[$index + 2];

                // 次の行の先頭へ移動
                $index += 3;
            }

            // 元の配列を上書き
            $questions = $newQuestions;
        }
        return $questions;
    }

    /**
     * ③ 結果保存
     */
    public function saveResult(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'Login required'], 401);
        }

        $game_id = $request->game_id ?? 1;
        $setting_id = $request->setting_id;
        $mode = $request->mode;   // ← 追加

        /**
         * ① 保存値を mode で切り替え
         * 60s-count → score を保存
         * timeattack → play_time を保存
         */
        if ($mode === '60s-count') {

            GameResult::create([
                'user_id' => $user->id,
                'game_id' => $game_id,
                'setting_id' => $setting_id,
                'score' => $request->score,
                'play_time' => null,
            ]);

            // streak 更新（1日1回だけ増える仕様）
        $today = Carbon::today();
        $lastPlayed = $user->last_played_at
            ? Carbon::parse($user->last_played_at)->startOfDay()
            : null;

        if (!$lastPlayed || !$lastPlayed->equalTo($today)) {

            if ($lastPlayed && $lastPlayed->equalTo($today->copy()->subDay())) {
                // 連続日
                $user->streak += 1;
            } else {
                // 初回 or 途切れた
                $user->streak = 1;
            }

            $user->last_played_at = $today;
            $user->save();
        }

            // ランキング：score 高い順（DESC）
            $orderColumn = 'score';
            $orderDirection = 'desc';

        } elseif ($mode === 'timeattack') {

            GameResult::create([
                'user_id' => $user->id,
                'game_id' => $game_id,
                'setting_id' => $setting_id,
                'score' => null,
                'play_time' => $request->play_time,  // 秒数（小数2桁）
            ]);

            // ランキング：play_time 少ない順（ASC）
            $orderColumn = 'play_time';
            $orderDirection = 'asc';
        }


        /**
         * ② ランキング（ユーザーごと最高値）
         */
        $top3 = GameResult::select(
                'user_id',
                DB::raw("MIN($orderColumn) as best_value") // ASC なら MIN、DESC なら MAX
            )
            ->where('game_id', $game_id)
            ->where('setting_id', $setting_id)
            ->groupBy('user_id')
            ->orderBy('best_value', $orderDirection)
            ->limit(3)
            ->get()
            ->map(function ($row) use ($orderColumn) {
                return [
                    'name'  => $row->user->name ?? 'NoName',
                    'value' => $row->best_value
                ];
            });

        /**
         * ③ 自分の最高値
         */
        $myBest = GameResult::where('user_id', $user->id)
            ->where('game_id', $game_id)
            ->where('setting_id', $setting_id)
            ->min($orderColumn);  // ASC＝min, DESC＝max と同じ動き

        /**
         * ④ 自分の順位
         */
        $myRank = GameResult::select(
                'user_id',
                DB::raw("MIN($orderColumn) as best_value")
            )
            ->where('game_id', $game_id)
            ->where('setting_id', $setting_id)
            ->groupBy('user_id')
            ->having('best_value', $orderDirection === 'asc' ? '<' : '>', $myBest)
            ->count() + 1;

        return response()->json([
            'saved'       => true,
            'mode'        => $mode,
            'top3'        => $top3,
            'my_best'     => $myBest,
            'my_rank'     => $myRank
        ]);
    }
}
