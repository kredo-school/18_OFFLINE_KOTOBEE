<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('payments', function (Blueprint $table) {

            // price：随時支払い用 → サブスクでは必須でないので nullable
            $table->decimal('price', 6, 2)->nullable()->change();

            // transaction_id：随時決済専用 → サブスクでは不要のため nullable
            $table->string('transaction_id')->nullable()->change();

            // 🟦 サブスク専用カラム ----------------------------------------------------------------------------------------------------------------

            // PayPal Subscription ID（例：I-XXXXXX）
            $table->string('subscription_id')->nullable()->after('plan_type');

            // PayPal Plan ID（例：P-XXXXXX）
            $table->string('paypal_plan_id')->nullable()->after('subscription_id');

            // trial 終了日（最初の請求日）
            $table->date('trial_ends_at')->nullable()->after('paypal_plan_id');

            // 次回請求日（Webhookにより自動更新）
            $table->date('next_billing_date')->nullable()->after('trial_ends_at');

            // サブスクステータス（active, cancelled, suspended, pending...）
            $table->string('payment_status')->default('pending')->change();
        });
    }

    public function down()
    {
        Schema::table('payments', function (Blueprint $table) {

            // 元の状態へ戻す（必要なら）
            $table->decimal('price', 6, 2)->nullable(false)->change();
            $table->string('transaction_id')->nullable(false)->change();
            $table->string('payment_status')->default(null)->change();

            $table->dropColumn([
                'subscription_id',
                'paypal_plan_id',
                'trial_ends_at',
                'next_billing_date',
            ]);
        });
    }
};
