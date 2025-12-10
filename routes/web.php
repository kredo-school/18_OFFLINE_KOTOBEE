<?php

use App\Http\Controllers\GroupController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KanaGameController;

// PayPal SDK check用に記述
// use Srmklive\PayPal\Services\PayPal as PayPalClient;
use App\Http\Controllers\PaymentController;

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
    
    // Create Group 画面
    Route::get('/group/create', [GroupController::class, 'create'])->name('group.create');
    // 仮の GroupAdmin ダッシュボード画面
    Route::get('/group/dashboard', function () {return 'Dashboard (Coming Soon)';})->name('group.dashboard');

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
});




// // PayPal SDK 動作確認用ルート
// Route::get('/paypal-test', function () {

//     // PayPal クライアント生成
//     $paypal = new PayPalClient;

//     // 設定読み込み（config/paypal.php を使用）
//     $paypal->setApiCredentials(config('paypal'));

//     // アクセストークン取得（ここで SDK 接続を実行）
//     try {
//         $token = $paypal->getAccessToken();

//         return response()->json([
//             'status' => 'OK',
//             'message' => 'PayPal SDK 接続成功！',
//             'access_token_sample' => substr($token['access_token'], 0, 20) . '...',
//             'token_type' => $token['token_type'],
//             'expires_in' => $token['expires_in'],
//             'mode' => config('paypal.mode'),
//         ]);

//     } catch (\Exception $e) {

//         // 接続失敗（Credential エラーなど）
//         return response()->json([
//             'status' => 'ERROR',
//             'message' => $e->getMessage(),
//             'mode' => config('paypal.mode'),
//         ], 500);
//     }
// });
