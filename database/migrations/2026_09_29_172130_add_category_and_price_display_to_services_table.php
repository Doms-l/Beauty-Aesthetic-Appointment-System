<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add fields needed for the M. Cares service pricelist.
     */
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->string('category')->after('id');
            $table->string('price_display')->nullable()->after('price');
        });
    }

    /**
     * Remove the added fields if the migration is rolled back.
     */
    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn([
                'category',
                'price_display',
            ]);
        });
    }
};