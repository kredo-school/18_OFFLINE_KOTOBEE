<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'owner_id',
        'plan_type',
        'subscription_id',
        'paypal_plan_id',
        'trial_ends_at',
        'next_billing_date',
        'price',
        'transaction_id',
        'payment_status',
        'paid_at',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
    ];
}
