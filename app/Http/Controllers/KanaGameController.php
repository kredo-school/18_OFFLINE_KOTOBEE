<?php

namespace App\Http\Controllers;

use App\Models\GameSetting;
use App\Models\KanaQuestion;
use App\Models\GameResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

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

        // ① スコア保存
        GameResult::create([
            'user_id' => $user->id,
            'game_id' => $game_id,
            'setting_id' => $setting_id,
            'created_by_admin_id' => null,
            'score' => $request->score,
            'play_time' => null,
        ]);

        // ② setting_id & game_id 内での最高スコアランキング（ユーザーごと1件）
        $top3 = GameResult::select('user_id', DB::raw('MAX(score) as max_score'))
            ->where('game_id', $game_id)
            ->where('setting_id', $setting_id)
            ->groupBy('user_id')
            ->orderBy('max_score', 'desc')
            ->limit(3)
            ->get()
            ->map(function ($row) {
                return [
                    'name'  => $row->user->name ?? 'NoName',
                    'score' => $row->max_score
                ];
            });

        // ③ 自分の最高スコア
        $myBest = GameResult::where('user_id', $user->id)
            ->where('game_id', $game_id)
            ->where('setting_id', $setting_id)
            ->max('score');

        // ④ 自分の順位（同じ条件でカウント）
        $myRank = GameResult::select('user_id', DB::raw('MAX(score) as max_score'))
            ->where('game_id', $game_id)
            ->where('setting_id', $setting_id)
            ->groupBy('user_id')
            ->having('max_score', '>', $myBest)
            ->count() + 1;

        return response()->json([
            'saved'    => true,
            'top3'     => $top3,
            'my_score' => $myBest,
            'my_rank'  => $myRank
        ]);
    }
}
