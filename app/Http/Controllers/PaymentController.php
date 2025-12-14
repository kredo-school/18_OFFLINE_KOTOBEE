<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Group;
use App\Models\Payment;
// use App\Services\PayPalService;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PaymentController extends Controller
{
    // private $paypalService;

    // public function __construct(PayPalService $paypalService)
    // {
    //     $this->paypalService = $paypalService;
    // }

    public function createPayment(Request $request)
    {
        $user = auth()->user();

        // ① group 仮作成
        $group = Group::create([
            'owner_id' => $user->id,
            'name'     => $request->name,
            'secret'   => $request->secret,
            'note'     => $request->note ?? null,
            'status'   => 'pending',
        ]);

        // plan → 数値化（DB用）
        $planType = [
            'basic'    => 1,
            'standard' => 2,
            'premium'  => 3,
        ][$request->plan] ?? null;

        if (!$planType) {
            abort(400, 'Invalid plan');
        }

        // ② payment 仮作成
        $payment = Payment::create([
            'owner_id'       => $user->id,
            'group_id'       => $group->id,
            'plan_type'      => $planType,
            'payment_status' => 'pending',
        ]);

        // ③ PayPal Plan ID 解決
        $plans = config('paypal.plans');

        if (!isset($plans[$request->plan])) {
            abort(500, 'PayPal plan not configured');
        }

        $planId = $plans[$request->plan];

        // ④ PayPal Subscription 作成
        $paypal = new PayPalClient;
        $paypal->setApiCredentials(config('paypal'));
        $paypal->getAccessToken();

        $subscription = $paypal->createSubscription([
            'plan_id' => $planId,
            'application_context' => [
                'return_url' => route('group.pending'),
                'cancel_url' => route('group.create'),
            ],
        ]);

        if (!isset($subscription['id'])) {
            \Log::error('PayPal subscription failed', $subscription);
            abort(500, 'PayPal subscription creation failed');
        }

        // ⑤ subscription_id 保存
        $payment->update([
            'subscription_id' => $subscription['id'],
        ]);

        // ⑥ PayPalへ
        foreach ($subscription['links'] as $link) {
            if ($link['rel'] === 'approve') {
                return redirect()->away($link['href']);
            }
        }

        abort(500, 'PayPal approve link not found');
    }
}