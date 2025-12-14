<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PayPalWebhookController;

Route::post('/paypal/webhook', [PayPalWebhookController::class, 'handle']);
