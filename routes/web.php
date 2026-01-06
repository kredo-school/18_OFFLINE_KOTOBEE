<?php

use App\Http\Controllers\AdminGrammarController;
use App\Http\Controllers\AdminVocabController;
use App\Http\Controllers\GrammarController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\VocabGameController;
use App\Http\Controllers\GrammarGameController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KanaGameController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VocabularyController;
use App\Http\Controllers\StudentGroupController;

// PayPal SDK check用に記述
// use Srmklive\PayPal\Services\PayPal as PayPalClient;
use App\Http\Controllers\PaymentController;

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
Route::middleware(['auth'])->group(function () {

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

    // Create Group 画面
    Route::get('/group/create', [GroupController::class, 'create'])->name('group.create');
    // 仮の GroupAdmin ダッシュボード画面
    Route::get('/group/dashboard', function () {
        return 'Dashboard (Coming Soon)';
    })->name('group.dashboard');

    // 送信（決済はまだ未実装）
    Route::post('/group/store', [GroupController::class, 'store'])->name('group.store');
    // Step1: 支払い開始
    Route::get('/payment/create', [PaymentController::class, 'createPayment'])->name('payment.create');
    // Step2: PayPalから戻る（成功）
    Route::get('/payment/success', [PaymentController::class, 'paymentSuccess'])->name('payment.success');
    // Step3: キャンセル
    Route::get('/payment/cancel', [PaymentController::class, 'paymentCancel'])->name('payment.cancel');
    // PayPal 完了コールバック
    Route::get('/payment/complete', [PaymentController::class, 'complete'])->name('payment.complete');


    ///////// Grammarゲーム //////////
    /*** grammarゲーム：ステージ選択画面 ***/
    Route::get('/grammar/stages', [GrammarGameController::class, 'stages'])
        ->name('grammar.stages');

    /*** grammarゲーム：ゲーム開始用API ***/
    Route::get('/api/grammar/start/{id}', [GrammarGameController::class, 'start'])
        ->name('grammar.start');
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

    //////profile///////
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::get('/vocabulary', [VocabularyController::class, 'index'])->name('vocabulary.index');
    Route::get('/grammar', [GrammarController::class, 'index'])->name('grammar.index');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/edit', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    ///////// 生徒のグループ機能 /////////    
    /*** グループ検索画面 ***/
    Route::get('/group/search', [StudentGroupController::class, 'search'])
        ->name('group.search');

    /*** グループ申請画面 ***/
    Route::get('group/join/{group}', [StudentGroupController::class, 'join'])
        ->name('group.join');

    /*** グループ申請処理 ***/
    Route::post('group/join/process/{group}', [StudentGroupController::class, 'join_submit'])
        ->name('group.join.submit');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/admin/vocab/create', [AdminVocabController::class, 'create'])->name('admin.vocab.create');
    Route::post('/admin/vocab/store', [AdminVocabController::class, 'store'])->name('admin.vocab.store');
});



Route::middleware(['auth'])->prefix('admin')->group(function () {
    // Grammar 問題作成画面
    Route::get('/grammar/create', [AdminGrammarController::class, 'create'])->name('admin.grammar.create');
    // Grammar 問題保存
    Route::post('/grammar/store', [AdminGrammarController::class, 'store'])->name('admin.grammar.store');
});
