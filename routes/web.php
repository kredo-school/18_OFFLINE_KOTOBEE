<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KanaGameController;

/* --- 初期ページ --- */
Route::get('/', function () {
    return redirect()->route('login');
})->middleware('guest');

/* --- Auth --- */
Auth::routes();

/* --- Auth 後の画面 --- */
Route::middleware(['auth'])->group(function() {

    /*** ▼ ゲーム選択画面（ログイン後に最初に表示） ▼ ***/
    Route::get('/game/select', function () {
        return view('game.select');   // ← select.blade.php を表示
    })->name('game.select');

    /*** ▼ kanaゲーム：オプション選択画面 ▼ ***/
    Route::get('/kana/options', [KanaGameController::class, 'options'])
        ->name('kana.options');

    /*** ▼ kanaゲーム：設定 ID を指定してゲーム開始 ▼ ***/
    Route::get('/kana/start/{id}', [KanaGameController::class, 'start'])
        ->name('kana.start');

    /*** ▼ kanaゲーム：結果データ保存 ▼ ***/
    Route::post('/game/kana/save',  [KanaGameController::class, 'saveResult'])
        ->name('kana.saveResult');
});
