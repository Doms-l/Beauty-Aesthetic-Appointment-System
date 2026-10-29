<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * starts_at / ends_at  = when the promo runs (empty = no limit)
     * slots                = how many clients can avail it (empty = unlimited)
     */
    public function up(): void
    {
        Schema::table('promos', function (Blueprint $table) {
            $table->dateTime('starts_at')->nullable()->after('is_active');
            $table->dateTime('ends_at')->nullable()->after('starts_at');
            $table->unsignedSmallInteger('slots')->nullable()->after('ends_at');
        });

        Schema::table('appointments', function (Blueprint $table) {
            $table->foreignId('promo_id')
                ->nullable()
                ->after('service_id')
                ->constrained('promos')
                ->nullOnDelete();

            $table->index(['promo_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropIndex(['promo_id', 'status']);
            $table->dropConstrainedForeignId('promo_id');
        });

        Schema::table('promos', function (Blueprint $table) {
            $table->dropColumn(['starts_at', 'ends_at', 'slots']);
        });
    }
};
