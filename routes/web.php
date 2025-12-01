<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KanaGameController;

Route::get('/', function () {
    return redirect()->route('login');
})->middleware('guest');

Auth::routes();

Route::middleware(['auth'])->group(function() {

    // ① オプション一覧
    Route::get('/kana/options', [KanaGameController::class, 'options'])
        ->name('kana.options');

    // ② 設定 ID を指定したゲーム開始
    Route::get('/kana/start/{id}', [KanaGameController::class, 'start'])
        ->name('kana.start');

});


// Route::get('/', function () {
//     return redirect()->route('login');
// })->middleware('guest');

// Auth::routes();

// // Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Route::middleware('auth')->group(function () {
//     Route::get('/hiragana', [QuestionController::class, 'hiragana'])->name('hiragana');
//     Route::get('/katakana', [QuestionController::class, 'katakana'])->name('katakana');
// });