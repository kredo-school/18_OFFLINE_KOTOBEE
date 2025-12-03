<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\VocabGameController;
use Illuminate\Support\Facades\Route;




/* Home / Dashboard */
Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

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

});
