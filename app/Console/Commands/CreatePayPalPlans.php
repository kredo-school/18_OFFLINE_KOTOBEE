<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class CreatePayPalPlans extends Command
{
    protected $signature = 'app:create-paypal-plans';
    protected $description = 'Create PayPal Product and Subscription Plans (Sandbox Only)';

    public function handle()
    {
        $this->info("=== PayPal Sandbox: Product + Plans 作成開始 ===");

        $clientId = env('PAYPAL_CLIENT_ID');
        $secret   = env('PAYPAL_SECRET');
        $baseUrl  = env('PAYPAL_BASE_URL', 'https://api-m.sandbox.paypal.com');

        if (!$clientId || !$secret) {
            $this->error("ERROR: PAYPAL_CLIENT_ID または PAYPAL_SECRET がありません");
            return Command::FAILURE;
        }

        // ---- アクセストークン取得 ----
        $token = Http::asForm()
            ->withBasicAuth($clientId, $secret)
            ->post("$baseUrl/v1/oauth2/token", [
                'grant_type' => 'client_credentials'
            ]);

        if (!$token->successful()) {
            $this->error("トークン取得失敗:");
            $this->error($token->body());
            return Command::FAILURE;
        }

        $accessToken = $token->json()['access_token'];

        // ---- Product 作成 ----
        $this->info("Product 作成中...");

        $productPayload = [
            'name' => 'Japanese Learning Subscription',
            'description' => 'Online Japanese subscription service',
            'type' => 'SERVICE',
            'category' => 'EDUCATIONAL_AND_TEXTBOOKS'
        ];

        $productRes = Http::withToken($accessToken)
            ->post("$baseUrl/v1/catalogs/products", $productPayload);

        if (!$productRes->successful()) {
            $this->error("Product 作成失敗:");
            $this->error($productRes->body());
            return Command::FAILURE;
        }

        $productId = $productRes->json()['id'];
        $this->info("→ Product ID = $productId");

        // ---- 3プラン作成 ----
        $plans = [
            'basic' => 5,
            'standard' => 10,
            'premium' => 20,
        ];

        foreach ($plans as $name => $price) {

            $this->info("Plan 作成中: $name ($$price)");

            $payload = [
                'product_id' => $productId,
                'name' => ucfirst($name) . " Plan",
                'description' => ucfirst($name) . " monthly subscription",

                // 12/13検証のために下記のコードを追加：あとで削除
                'billing_cycles' => [
                    // ---- 定期課金（月1回）----
                    [
                        'frequency' => [
                            'interval_unit' => 'MONTH',
                            'interval_count' => 1,
                        ],
                        'tenure_type' => 'REGULAR',
                        'sequence' => 1,
                        'total_cycles' => 0,
                        'pricing_scheme' => [
                            'fixed_price' => [
                                'value' => (string)$price,
                                'currency_code' => 'USD'
                            ]
                        ]
                    ],
                ],

                'payment_preferences' => [
                    'auto_bill_outstanding' => true,
                    'setup_fee_failure_action' => 'CONTINUE',
                    'payment_failure_threshold' => 3,
                ],

            // 12/13検証のために下記のコードをコメントアウト
            //     'billing_cycles' => [
            //         // ---- 無料トライアル（30日）----
            //         [
            //             'frequency' => [
            //                 'interval_unit' => 'DAY',
            //                 'interval_count' => 30,
            //             ],
            //             'tenure_type' => 'TRIAL',
            //             'sequence' => 1,
            //             'total_cycles' => 1,
            //             'pricing_scheme' => [
            //                 'fixed_price' => [
            //                     'value' => '0',
            //                     'currency_code' => 'USD'
            //                 ]
            //             ]
            //         ],

            //         // ---- 定期課金（月1回）----
            //         [
            //             'frequency' => [
            //                 'interval_unit' => 'MONTH',
            //                 'interval_count' => 1,
            //             ],
            //             'tenure_type' => 'REGULAR',
            //             'sequence' => 2,
            //             'total_cycles' => 0,
            //             'pricing_scheme' => [
            //                 'fixed_price' => [
            //                     'value' => (string)$price,
            //                     'currency_code' => 'USD'
            //                 ]
            //             ]
            //         ],

            //     ],
            //     'payment_preferences' => [
            //         'auto_bill_outstanding' => true,
            //         'setup_fee_failure_action' => 'CONTINUE',
            //         'payment_failure_threshold' => 3,
            //     ],
            ];

            $res = Http::withToken($accessToken)
                ->post("$baseUrl/v1/billing/plans", $payload);

            if (!$res->successful()) {
                $this->error("Plan $name 作成失敗:");
                $this->error($res->body());
                continue;
            }

            $planId = $res->json()['id'];
            $this->info("→ $name Plan ID: $planId");
        }

        $this->info("=== 完了：3つのプラン作成 ===");
        return Command::SUCCESS;
    }
}
