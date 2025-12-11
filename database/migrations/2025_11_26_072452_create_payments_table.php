<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            // Whose contract
            $table->foreignId('owner_id')->constrained('users');

            // plan type: 1=basic, 2=standard, 3=premium, 4=custom
            $table->integer('plan_type');

            // price
            $table->decimal('price', 6, 2);

            // transaction ID (e.g. Stripe, PayPal)
            $table->string('transaction_id', 255);

            // payment status: 1=pending, 2=paid, 3=failed, 4=refunded
            $table->integer('payment_status');

            $table->dateTime('paid_at')->nullable();

            $table->timestamps();

            // --- optional: check constraints (MySQL 8+)
            // $table->check('plan_type IN (1,2,3,4)');
            // $table->check('payment_status IN (1,2,3,4)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
