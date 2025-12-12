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
        Schema::create('group_members', function (Blueprint $table) {
            $table->id();

            // foreign keys
            $table->foreignId('group_id')->constrained('groups');
            $table->foreignId('user_id')->constrained('users');

            // member status (1=pending, 2=approved, 3=left)
            $table->integer('status');

            $table->timestamps();

            // unique index to prevent double enrollment
            $table->unique(['group_id', 'user_id']);

            // optional: check constraint (MySQL 8+ only)
            // $table->check('status IN (1, 2, 3)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('group_members');
    }
};
