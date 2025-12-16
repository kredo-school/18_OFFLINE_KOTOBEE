<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\VocabGameController;
use App\Http\Controllers\GrammarGameController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KanaGameController;

/* --- 初期ページ --- */
Route::get('/', function () {
    return redirect()->route('login');
})->middleware('guest');

/* --- Auth --- */
Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

/*** vocabゲーム：ゲームスタートmodal ***/
Route::get('/vocab/start_page/{stage_id}', [HomeController::class, 'start_page'])
    ->name('vocab.start_page');

/* Vocabulary Game */
Route::prefix('vocab')->middleware('auth')->group(function () {

    // ステージ開始
    Route::get('/start/{stage}', [VocabGameController::class, 'start'])
        ->name('vocab.start');

    // 現在の問題表示
    Route::get('/question', [VocabGameController::class, 'showQuestion'])
        ->name('vocab.show');
    
    // 4択チェック
    Route::post('/check-choice', [VocabGameController::class, 'checkChoice'])
        ->name('vocab.checkChoice');

    // かな並べ替えチェック
    Route::post('/check-kana', [VocabGameController::class, 'checkKana'])
        ->name('vocab.checkKana');

    Route::post('/vocab/next', [VocabGameController::class, 'next'])->name('vocab.next');

    Route::get('/vocab/finish', [VocabGameController::class, 'finish'])->name('vocab.finish');

});

/* --- Auth 後の画面 --- */
Route::middleware(['auth'])->group(function() {

    /*** ▼ ゲーム選択画面（ログイン後に最初に表示） ▼ ***/
    Route::get('/game/select', function () {
        return view('game.select');   // ← select.blade.php を表示
    })->name('game.select');

    /*** ▼ kanaゲーム：オプション選択画面 ▼ ***/
    Route::get('/kana/options', [KanaGameController::class, 'options'])
        ->name('kana.options');

    /*** kanaゲーム：ゲームスタート画面 ***/
    Route::get('/kana/start_page/{setting_id}', [KanaGameController::class, 'start_page'])
        ->name('kana.start_page');

    /*** ▼ kanaゲーム：設定 ID を指定してゲーム開始 ▼ ***/
    Route::get('/kana/start/{id}', [KanaGameController::class, 'start'])
        ->name('kana.start');

    /*** ▼ kanaゲーム：結果データ保存 ▼ ***/
    Route::post('/game/kana/save',  [KanaGameController::class, 'saveResult'])
        ->name('kana.saveResult');
        
    ///////// Grammarゲーム //////////
    /*** grammarゲーム：ステージ選択画面 ***/
    Route::get('/grammar/stages', [GrammarGameController::class, 'stages'])
        ->name('grammar.stages');

    /*** grammarゲーム：ゲームスタート画面 ***/
    Route::get('/grammar/start_page/{stage_id}', [GrammarGameController::class, 'start_page'])
        ->name('grammar.start_page');

    /*** grammarゲーム：ゲーム画面表示用 ***/
    Route::get('/grammar/play/{stage_id}', [GrammarGameController::class, 'play'])
        ->name('grammar.play');

    /*** grammarゲーム：ゲーム開始用API ***/
    Route::get('/api/grammar/start/{stage_id}', [GrammarGameController::class, 'start'])
        ->name('grammar.start');    

    /*** grammarゲーム:結果データ保存***/
    Route::post('/game/grammar/save', [GrammarGameController::class, 'save_result'])
        ->name('grammar.save_result');
});
