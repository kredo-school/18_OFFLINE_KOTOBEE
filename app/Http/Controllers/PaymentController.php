<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Group;
use App\Models\Payment;
use App\Services\PayPalService;
use Illuminate\Support\Facades\Auth;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PaymentController extends Controller
{
    private $paypalService;

    public function __construct(PayPalService $paypalService)
    {
        $this->paypalService = $paypalService;
    }


    /**
     * Step 1:
     * GroupデータをSessionに保存 → PayPalへ遷移
     */
    public function createPayment(Request $request)
    {
        $groupInput = $request->all();

        if (!isset($groupInput['price'])) {
            return redirect()->route('group.create')
                ->with('error', '価格情報がありません。');
        }

        session(['new_group' => $groupInput]);

        $paypal = new PayPalClient;
        $paypal->setApiCredentials(config('paypal'));
        $paypal->getAccessToken();

        $order = $paypal->createOrder([
            'intent' => 'CAPTURE',
            'purchase_units' => [
                [
                    'amount' => [
                        'currency_code' => 'USD',
                        'value' => $groupInput['price']
                    ]
                ]
            ],
            'application_context' => [
                'cancel_url' => route('payment.cancel'),
                'return_url' => route('payment.success'),
            ]
        ]);

        foreach ($order['links'] as $link) {
            if ($link['rel'] === 'approve') {
                return redirect()->away($link['href']);
            }
        }

        return back()->with('error', 'PayPal の決済ページを生成できませんでした。');
    }


    /**
     * Step2:
     * 支払い成功 → DB保存 → group.create に戻す(back相当)
     */
    public function paymentSuccess(Request $request)
    {
        $orderId = $request->query('token');

        // PayPalからの結果を1回だけ取得
        $response = $this->paypalService->captureOrder($orderId);

        // Session の Group データ
        $groupData = session('new_group');

        $userId = Auth::id();

        // Group 保存
        $group = Group::create([
            'owner_id' => $userId,
            'name'     => $groupData['name'],
            'secret'   => $groupData['secret'],
            'note'     => $groupData['note'] ?? null,
        ]);

        // Plan数値化
        $plan_type = [
            'basic'    => 1,
            'standard' => 2,
            'premium'  => 3,
        ][$groupData['plan']] ?? 1;

        // Payment 保存
        Payment::create([
            'owner_id'       => $userId,
            'group_id'       => $group->id,
            'plan_type'      => $plan_type,
            'price'          => $groupData['price'],
            'transaction_id' => $response['id'] ?? null,
            'payment_status' => $response['status'] ?? null,
            'paid_at'        => now(),
            'paypal_order'   => json_encode($response),
        ]);

        session()->forget('new_group');

        // create_group.blade へ成功メッセージのみを渡す
        return redirect()
                ->route('group.create')
                ->with('payment_success', true);
    }


    /**
     * キャンセル
     */
    public function paymentCancel()
    {
        return redirect()->route('group.create')
            ->with('payment_failed', true);
    }

}
