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
        Schema::table('users', function (Blueprint $table) {
            $table->string('avatar_url', 255)->nullable()->after('password');
            $table->integer('role')->default(1)->after('avatar_url');  // 1=student,2=group_admin,3=admin
            $table->foreignId('group_id')->nullable()->after('role')->constrained('groups');
            $table->integer('prefecture_id')->nullable()->after('group_id');
            $table->dateTime('acquired_at')->nullable()->after('prefecture_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'avatar_url',
                'role',
                'group_id',
                'prefecture_id',
                'acquired_at',
            ]);
        });
    }
};
