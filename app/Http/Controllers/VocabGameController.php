<?php

namespace App\Http\Controllers;

use App\Models\VocabQuestion;
use App\Models\GameResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class VocabGameController extends Controller
{
    /**
     * ステージ開始
     */
    public function start($stageId)
    {
        // 5単語取得（重複なし）
        $words = VocabQuestion::where('stage_id', $stageId)
            ->inRandomOrder()
            ->limit(5)
            ->get();

        if ($words->count() < 5) {
            abort(404, "このステージには5単語ありません");
        }

        // 問題セット作成（4択 → 並べ替え） 
        $questionSet = [];
        foreach ($words as $word) {
            $questionSet[] = ['type' => 'choice', 'word' => $word];
            $questionSet[] = ['type' => 'kana', 'word' => $word];
        }

        session([
            'vocab_questions' => array_values($questionSet),
            'vocab_index' => 0,
            'vocab_start_time' => microtime(true),
            'vocab_stage_id' => $stageId,
        ]);

        return $this->showQuestion();
    }

    /**
     * 現在の問題を表示
     */
    public function showQuestion()
    {
        $questions = session('vocab_questions', []);
        $index = session('vocab_index', 0);

        if ($index >= count($questions)) {
            return view('vocab.kana', [
                'question' => null,
                'chars' => [],
                'shuffled' => [],
                'isLast' => true, // モーダル表示用
                'finishMode' => true, // JS に finish() の呼び出しを伝える
            ]);
        }


        $current = $questions[$index];
        $word = $current['word'];

        // ★ 最終問題フラグ
        $isLast = ($index === count($questions) - 1);

        if ($current['type'] === 'choice') {
            $choices = $this->makeChoices($word);

            return view('vocab.choice', [
                'question' => $word,
                'choices' => $choices,
                'isLast' => $isLast, // ★ 追加
            ]);
        }

        if ($current['type'] === 'kana') {
            $chars = $this->splitUnicodeChars($word->word);

            $minChoices = 6;

            if (count($chars) < $minChoices) {
                $stage = $word->stage_id;
                $otherWords = VocabQuestion::where('stage_id', $stage)
                    ->where('id', '<>', $word->id)
                    ->pluck('word')
                    ->toArray();

                $dummyChars = [];
                foreach ($otherWords as $ow) {
                    $dummyChars = array_merge($dummyChars, $this->splitUnicodeChars($ow));
                }

                $dummyChars = array_unique(array_diff($dummyChars, $chars));
                shuffle($dummyChars);

                $need = $minChoices - count($chars);
                $dummyChars = array_slice($dummyChars, 0, $need);

                $choices = array_merge($chars, $dummyChars);
            } else {
                $choices = $chars;
            }

            shuffle($choices);

            return view('vocab.kana', [
                'question' => $word,
                'chars' => $chars,
                'shuffled' => $choices,
                'isLast' => $isLast, // ★ 追加
            ]);
        }
    }

    /**
     * 4択チェック（不正解なら進まない）
     */
    public function checkChoice(Request $request)
    {
        $questions = session('vocab_questions', []);
        $index = session('vocab_index', 0);

        if ($index >= count($questions)) {
            return $this->finish();
        }

        $current = $questions[$index];
        $word = $current['word'];

        // ❌ 不正解 → stay + error を渡す
        if ($request->answer !== $word->word) {
            return redirect()->route('vocab.show')
                ->with('error', true);
        }

        // ⭕ 正解 → stay したまま正解フラグ送る（まだ進めない）
        return redirect()->route('vocab.show')
            ->with('correct', true);
    }

    public function checkKana(Request $request)
    {
        $questions = session('vocab_questions', []);
        $index = session('vocab_index', 0);
        $current = $questions[$index];
        $word = $current['word'];

        $correct = implode('', $this->splitUnicodeChars($word->word));
        $answer = $request->answer;

        $isLast = ($index === count($questions) - 1);

        if ($answer === $correct) {

            session(['last_answer' => $answer]);

            // ★ 最終問題は index を進める（finish() 発火のため）
            if ($isLast) {
                session(['vocab_index' => $index + 1]);
            }

            session(['isLast' => $isLast]);

            return redirect()->route('vocab.show')->with('correct', true);
        }

        session()->forget('last_answer');
        session(['isLast' => false]);

        return redirect()->route('vocab.show')->with('error', true);
    }

    /* --------------------------
        内部ロジック
    --------------------------*/

    /**
     * 4択生成（重複なし）
     */
    private function makeChoices($word)
    {
        $stage = $word->stage_id;

        // 同じステージの単語を取得（重複除外）
        $stageWords = VocabQuestion::where('stage_id', $stage)
            ->pluck('word')
            ->unique()
            ->values()
            ->toArray();

        // 正解以外の候補
        $dummyPool = array_filter($stageWords, fn($w) => $w !== $word->word);

        // ダミーを3つ取得（不足しても後で補完する）
        $dummy = collect($dummyPool)->shuffle()->take(3)->values()->toArray();

        // 候補が足りなければ "全ステージ" から補完
        if (count($dummy) < 3) {
            $other = VocabQuestion::pluck('word')
                ->unique()
                ->values()
                ->toArray();

            foreach ($other as $o) {
                if (count($dummy) >= 3) break;
                if ($o !== $word->word && !in_array($o, $dummy)) {
                    $dummy[] = $o;
                }
            }
        }

        // 正解 + ダミー3つをセット
        $choices = array_merge([$word->word], $dummy);

        // 安全のため4つに切り詰める
        $choices = array_slice($choices, 0, 4);

        // シャッフル
        shuffle($choices);

        return $choices;
    }


    /**
     * UTF-8 分割
     */
    private function splitUnicodeChars($string)
    {
        return preg_split('//u', $string, -1, PREG_SPLIT_NO_EMPTY);
    }

    public function next()
    {
        // 前の回答とフラグを消す（重要）
        session()->forget('last_answer');
        session()->forget('correct');
        session()->forget('error');

        $index = session('vocab_index', 0);
        session(['vocab_index' => $index + 1]);

        return redirect()->route('vocab.show');
    }



    /**
     * 全問題終了
     */
    public function finish()
    {
        $start = session('vocab_start_time');
        $stageId = session('vocab_stage_id');

        $time = microtime(true) - $start;
        $time = min(round($time, 2), 9999.99);

        $user = auth()->user();
        $userId = $user->id;

        // 結果を保存
        GameResult::create([
            'user_id' => $userId,
            'game_id' => 2,
            'setting_id' => null,
            'created_by_admin_id' => null,
            'vcab_stage_id' => $stageId,
            'score' => null,
            'play_time' => round($time, 2),
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


        // ランキング処理
        $game_id = 2;
        $setting_id = null;
        $orderColumn = 'play_time';
        $orderDirection = 'asc'; // 小さい方が良い

        $top3 = GameResult::with('user')
            ->select('user_id', DB::raw("MIN($orderColumn) as best_value"))
            ->where('game_id', $game_id)
            ->where('setting_id', $setting_id)
            ->groupBy('user_id')
            ->orderBy('best_value', $orderDirection)
            ->limit(3)
            ->get()
            ->map(function ($row) {
                return [
                    'name' => optional($row->user)->name ?? 'NoName',
                    'value' => $row->best_value
                ];
            });

        $myBest = GameResult::where('user_id', $userId)
            ->where('game_id', $game_id)
            ->where('setting_id', $setting_id)
            ->min($orderColumn);

        $myRank = GameResult::select('user_id', DB::raw("MIN($orderColumn) as best_value"))
            ->where('game_id', $game_id)
            ->where('setting_id', $setting_id)
            ->groupBy('user_id')
            ->havingRaw("MIN($orderColumn) < ?", [$myBest])
            ->count() + 1;

        // JSONで返す
        return response()->json([
            'saved' => true,
            'time' => round($time, 2),
            'my_best' => $myBest,
            'my_rank' => $myRank,
            'top3' => $top3,
            'streak' => $user->streak,
        ]);
    }
}
