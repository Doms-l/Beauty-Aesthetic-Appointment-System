<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * cancel_strikes = how many appointments the client cancelled
     *                  (reset to 0 when an admin unbans the account)
     * banned_at      = when the account was banned (null = not banned)
     * ban_reason     = short note shown to the admin
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedTinyInteger('cancel_strikes')->default(0);
            $table->timestamp('banned_at')->nullable();
            $table->string('ban_reason')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['cancel_strikes', 'banned_at', 'ban_reason']);
        });
    }
};
