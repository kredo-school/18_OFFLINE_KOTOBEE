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
        Schema::create('paymemt_groups', function (Blueprint $table) {
            // Foreign keys
            $table->foreignId('payment_id')->constrained('payments')->onDelete('cascade');
            $table->foreignId('group_id')->constrained('groups')->onDelete('cascade');

            // No timestamps in your design, but you can add if needed:
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paymemt_groups');
    }
};
