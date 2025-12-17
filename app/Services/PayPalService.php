<?php

namespace App\Services;

use GuzzleHttp\Client;

class PayPalService
{
    private $client;
    private $clientId;
    private $clientSecret;

    public function __construct()
    {
        $this->client = new Client();
        $this->clientId = config('paypal.sandbox.client_id');
        $this->clientSecret = config('paypal.sandbox.client_secret');
    }

    private function getAccessToken()
    {
        $response = $this->client->post('https://api-m.sandbox.paypal.com/v1/oauth2/token', [
            'auth' => [$this->clientId, $this->clientSecret],
            'form_params' => ['grant_type' => 'client_credentials'],
        ]);

        $data = json_decode($response->getBody(), true);
        return $data['access_token'];
    }

    public function captureOrder($orderId)
    {
        $accessToken = $this->getAccessToken();

        $response = $this->client->post(
            "https://api-m.sandbox.paypal.com/v2/checkout/orders/{$orderId}/capture",
            [
                'headers' => [
                    'Authorization' => "Bearer {$accessToken}",
                    'Content-Type'  => 'application/json',
                ],
            ]
        );

        return json_decode($response->getBody(), true);
    }
}
