<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Extra services added by the admin to ONE existing visit
 * (same client, same day, same time). The price is copied here so an
 * old receipt never changes if the service price changes later.
 *
 * Safe to run again: a half-built leftover table from an interrupted
 * run is rebuilt, but a table that already holds data is never touched.
 */
return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('visit_extra_services')) {
            if (DB::table('visit_extra_services')->exists()) {
                return; // real data inside - keep it
            }

            Schema::dropIfExists('visit_extra_services'); // empty leftover - rebuild it cleanly
        }

        Schema::create('visit_extra_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('appointment_id')->constrained()->cascadeOnDelete();
            $table->foreignId('service_id')->constrained()->restrictOnDelete();
            $table->decimal('price', 10, 2);
            $table->timestamps();

            $table->unique(['appointment_id', 'service_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visit_extra_services');
    }
};
