<?php

namespace App\Http\Controllers;
use App\Models\Payment;
use App\Models\Group;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PayPalWebhookController extends Controller
{
    public function handle(Request $request)
    {
        // 🔹 ① Webhook 全体をまずログに出す（最重要）
        Log::info('PayPal Webhook Received', $request->all());

        $eventType = $request->input('event_type');

        // 🔹 ② Subscription が有効化されたとき
        if ($eventType === 'BILLING.SUBSCRIPTION.ACTIVATED') {

            $r = $request->input('resource');

            $payment = Payment::where('subscription_id', $r['id'])->first();

            if ($payment) {
                $payment->update([
                    'payment_status'    => 'active',
                    'paypal_plan_id'    => $r['plan_id'] ?? null,
                    'next_billing_date' => isset($r['billing_info']['next_billing_time'])
                        ? Carbon::parse($r['billing_info']['next_billing_time'])
                        : null,
                ]);

                // group を有効化
                Group::where('owner_id', $payment->owner_id)
                    ->where('status', 'pending')
                    ->update(['status' => 'active']);
            }
        }

        // 🔹 ③ 支払い完了時（金額確認用）
        if ($eventType === 'PAYMENT.SALE.COMPLETED') {

            $r = $request->input('resource');

            $payment = Payment::where('subscription_id', $r['billing_agreement_id'])->first();

            if ($payment) {
                $payment->update([
                    'transaction_id' => $r['id'],
                    'price'          => $r['amount']['total'],
                    'paid_at'        => now(),
                ]);
            }
        }

        return response()->json(['status' => 'ok'], 200);

    }
}
