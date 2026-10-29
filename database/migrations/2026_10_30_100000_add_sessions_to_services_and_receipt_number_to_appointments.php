<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {
            // how many sessions one purchase of this service covers (1 = a normal single service)
            $table->unsignedSmallInteger('sessions')->default(1);
        });

        Schema::table('appointments', function (Blueprint $table) {
            // given the first time a receipt is printed, then it never changes
            $table->string('receipt_number', 30)->nullable()->unique();
        });

        // The only package in the current price list
        DB::table('services')
            ->where('name', 'Free Stemcell Facial')
            ->update(['sessions' => 4]);
    }

    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropUnique(['receipt_number']);
            $table->dropColumn('receipt_number');
        });

        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn('sessions');
        });
    }
};
