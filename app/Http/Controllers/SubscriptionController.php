<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Payment;
use App\Models\Group;
use Carbon\Carbon;

class SubscriptionController extends Controller
{
    /**
     * ① グループ作成 → PayPal 支払いへ
     */
    public function start(Request $request)
    {
        $request->validate([
            'plan'   => 'required',
            'name'   => 'required',
            'secret' => 'required',
        ]);

        session([
            'pending_group' => [
                'plan'   => $request->plan,
                'name'   => $request->name,
                'secret' => $request->secret,
                'note'   => $request->note,
            ],
        ]);

        // 0. 画面で選択された plan を PayPal プランIDへ変換
        $planId = config("paypal.plans.{$request->plan}");

        if (!$planId) {
            Log::error("Invalid plan key: {$request->plan}");
            return back()->with('error', 'Invalid plan selected.');
        }

        // 1. Token
        $token = Http::asForm()->withBasicAuth(
            config('paypal.sandbox.client_id'),
            config('paypal.sandbox.client_secret')
        )->post(config('paypal.base_url') . '/v1/oauth2/token', [
            'grant_type' => 'client_credentials',
        ]);

        if (!$token->successful()) {
            Log::error('PayPal Token Error', $token->json());
            return back()->with('error', 'PayPal connection failed.');
        }

        $accessToken = $token['access_token'];

        // 2. Subscription 作成
        // ❗ subscriber/payment_source は送らない（PayPal error 対策）
        $payload = [
            "plan_id" => $planId, // ← ★ここ重要（PayPalのプランIDを送る）
            "application_context" => [
                // "brand_name" => "Yagi Japanese",
                "brand_name" => "KotoBee Japanese",
                "locale"     => "en-US",
                "return_url" => route('subscription.success'),
                "cancel_url" => route('subscription.cancel'),
                "user_action" => "SUBSCRIBE_NOW",
            ]
        ];

        $create = Http::withToken($accessToken)->post(
            config('paypal.base_url') . '/v1/billing/subscriptions',
            $payload
        );

        if (!$create->successful()) {
            Log::error('PayPal Subscription Create Error', $create->json());
            return back()->with('error', 'Subscription create failed.');
        }

        $data = $create->json();
        $approve = collect($data['links'])->firstWhere('rel', 'approve')['href'] ?? null;

        if (!$approve) {
            Log::error('PayPal Approval URL Missing', $data);
            return back()->with('error', 'Approval URL not found.');
        }

        return redirect($approve);
    }

    /**
     * ② PayPal return_url
     */
    public function success(Request $request)
    {
        Log::info('PayPal SUCCESS query', $request->all());

        $subscriptionId = $request->subscription_id ?? $request->token;

        if (!$subscriptionId) {
            return redirect()->route('dashboard')->with('error', 'Subscription ID missing.');
        }

        // pending data
        $data = session('pending_group');
        if (!$data) {
            return redirect()->route('dashboard')->with('error', 'Session expired.');
        }

        // Token
        $token = Http::asForm()->withBasicAuth(
            config('paypal.sandbox.client_id'),
            config('paypal.sandbox.client_secret')
        )->post(config('paypal.base_url') . '/v1/oauth2/token', [
            'grant_type' => 'client_credentials'
        ]);

        $accessToken = $token['access_token'];

        // Subscription 詳細取得
        $subRes = Http::withToken($accessToken)
            ->get(config('paypal.base_url') . "/v1/billing/subscriptions/{$subscriptionId}");

        if (!$subRes->successful()) {
            Log::error('PayPal Subscription Get Error', $subRes->json());
            return redirect()->route('dashboard')->with('error', 'Subscription verify failed.');
        }

        $sub = $subRes->json();

        // dd("id=", $sub['id'], "next_billing_time=", $sub['billing_info']['next_billing_time']);
        $subscriptionId = $sub['id'] ?? null;
        // dd($subscriptionId);
        $planId = $sub['plan_id'] ?? null;
        $status = $sub['status'] ?? 'unknown';

        $nextBilling = $sub['billing_info']['next_billing_time'] ?? null;
        $nextBillingDate = $nextBilling ? Carbon::parse($nextBilling) : null;

        // 切り分けのためのログ出力
        // logger('PayPal create response', $subRes);
        // logger('Subscription details response', $sub);

        // グループ作成
        Group::create([
            'name'   => $data['name'],
            'note'   => $data['note'],
            'owner_id' => auth()->id(),
            'secret' => $data['secret'],
        ]);

        $planMap = [
            'basic'    => 1,
            'standard' => 2,
            'premium'  => 3,
        ];

        $planType = $planMap[$data['plan']] ?? null;

        // payments 保存（あなたのテーブル構造に合わせる）
        Payment::create([
            'owner_id'          => auth()->id(),
            'plan_type'         => $planType,               // 1/2/3 の数字
            'subscription_id'   => $subscriptionId,
            'paypal_plan_id'    => $planId,
            'trial_ends_at'     => null,                    // 今は PayPal が返さない
            'next_billing_date' => $nextBillingDate,        // 取得できている！
            'price'             => null,                    // 課金前なので NULL
            'transaction_id'    => null,                    // 課金前なので NULL
            'payment_status'    => $status,                 // ACTIVE
            'paid_at'           => null,                    // 課金前なので NULL
        ]);

        session()->forget('pending_group');

        return redirect()->route('group.dashboard')->with('success', 'Subscription started!');
    }

    public function cancel()
    {
        return redirect()->route('group.create')->with('error', 'You canceled the subscription.');
    }
}
