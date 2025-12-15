<?php

use App\Http\Controllers\GroupController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\VocabGameController;
use App\Http\Controllers\GrammarGameController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KanaGameController;

// PayPal SDK check用に記述
// use Srmklive\PayPal\Services\PayPal as PayPalClient;
use App\Http\Controllers\PaymentController;         // 随時要
use App\Http\Controllers\SubscriptionController;    // サブスク用

/* --- 初期ページ --- */
Route::get('/', function () {
    return redirect()->route('login');
})->middleware('guest');

/* --- Auth --- */
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

    /*** ▼ kanaゲーム：設定 ID を指定してゲーム開始 ▼ ***/
    Route::get('/kana/start/{id}', [KanaGameController::class, 'start'])
        ->name('kana.start');

    /*** ▼ kanaゲーム：結果データ保存 ▼ ***/
    Route::post('/game/kana/save',  [KanaGameController::class, 'saveResult'])
        ->name('kana.saveResult');
    
    // Create Group 画面
    Route::get('/group/create', [GroupController::class, 'create'])->name('group.create');
    // 仮の GroupAdmin ダッシュボード画面
    Route::get('/group/dashboard', function () {return 'Dashboard (Coming Soon)';})->name('group.dashboard');


    /*** ▼ サブスクリプション関連 ▼ ***/
    Route::post('/subscription/start', [PaymentController::class, 'createPayment'])->name('subscription.start');
    // Route::post('/subscription/start', [SubscriptionController::class, 'start'])->name('subscription.start');
    // Route::get('/subscription/success', [SubscriptionController::class, 'success'])->name('subscription.success');
    // Route::get('/subscription/cancel', [SubscriptionController::class, 'cancel'])->name('subscription.cancel');

    // これは随時決済用
    // 送信（決済はまだ未実装）
    // Route::post('/group/store', [GroupController::class, 'store'])->name('group.store');

    // Step1: 支払い開始
    Route::get('/payment/create', [PaymentController::class, 'createPayment'])->name('payment.create');

    // Step2: PayPalから戻る（成功）
    // Route::get('/payment/success', [PaymentController::class, 'paymentSuccess'])->name('payment.success');

    // Step3: キャンセル
    // Route::get('/payment/cancel', [PaymentController::class, 'paymentCancel'])->name('payment.cancel');

    // PayPal 完了コールバック
    // Route::get('/payment/complete', [PaymentController::class, 'complete'])->name('payment.complete');

    // pending 画面
    Route::get('/group/pending', [GroupController::class, 'pending'])->name('group.pending');
    // pending ステータス確認API
    Route::get('/group/pending/status', [GroupController::class, 'pendingStatus'])->name('group.pending.status');


    ///////// Grammarゲーム //////////
    /*** grammarゲーム：ステージ選択画面 ***/
    Route::get('/grammar/stages', [GrammarGameController::class, 'stages'])
        ->name('grammar.stages');

    /*** grammarゲーム：ゲーム開始用API ***/
    Route::get('/api/grammar/start/{id}', [GrammarGameController::class, 'start'])
        ->name('grammar.start');
    
    /*** grammarゲーム：ゲーム画面表示用 ***/
    Route::get('/grammar/play/{id}', [GrammarGameController::class, 'play'])
        ->name('grammar.play');

    /*** grammarゲーム:結果データ保存***/
    Route::post('/game/grammar/save', [GrammarGameController::class, 'save_result'])
        ->name('grammar.save_result');
            
});