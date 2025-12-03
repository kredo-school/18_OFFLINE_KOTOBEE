<?php

namespace App\Http\Controllers;

use App\Models\VocabQuestion;
use App\Models\GameResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            return $this->finish();
        }

        $current = $questions[$index];
        $word = $current['word'];

        if ($current['type'] === 'choice') {
            // 4択生成（重複防止）
            $choices = $this->makeChoices($word);

            return view('vocab.choice', [
                'question' => $word,
                'choices' => $choices,
            ]);
        }

        if ($current['type'] === 'kana') {
            $chars = $this->splitUnicodeChars($word->word);

            $minChoices = 6;  // 最低6文字の選択肢にする

            if (count($chars) < $minChoices) {
                $stage = $word->stage_id;

                // 同じステージの他単語を取得（自分以外）
                $otherWords = VocabQuestion::where('stage_id', $stage)
                    ->where('id', '<>', $word->id)
                    ->pluck('word')
                    ->toArray();

                $dummyChars = [];

                foreach ($otherWords as $ow) {
                    $dummyChars = array_merge($dummyChars, $this->splitUnicodeChars($ow));
                }

                // 正解の文字は除外し、重複もなくす
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




    /**
     * 並べ替えチェック
     */
    public function checkKana(Request $request)
    {
        $questions = session('vocab_questions', []);
        $index = session('vocab_index', 0);
        $current = $questions[$index];
        $word = $current['word'];

        $correct = implode('', $this->splitUnicodeChars($word->word));

        // ❌ 不正解
        if ($request->answer !== $correct) {
            return redirect()->route('vocab.show')->with('error', true);
        }

        // ⭕ 正解 → CONTINUE 表示
        return redirect()->route('vocab.show')->with('correct', true);
    }


    /**
     * 全問題終了
     */
    public function finish()
    {
        $start = session('vocab_start_time');
        $time = microtime(true) - $start;
        $time = min(round($time, 2), 9999.99);

        GameResult::create([
            'user_id' => Auth::id(),
            'game_id' => 2,
            'setting_id' => null,
            'created_by_admin_id' => null,
            'score' => null,
            'play_time' => round($time, 2),
        ]);

        session()->forget([
            'vocab_questions',
            'vocab_index',
            'vocab_start_time'
        ]);

        return view('vocab.result', [
            'time' => round($time, 2)
        ]);
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
        $index = session('vocab_index', 0);
        session(['vocab_index' => $index + 1]);
        return redirect()->route('vocab.show');
    }
}
