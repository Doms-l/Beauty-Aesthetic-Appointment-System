<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The promo shown in the home banner, the dashboard slideshow
     * and the top announcement bar. Only one row is used.
     */
    public function up(): void
    {
        Schema::create('promos', function (Blueprint $table) {
            $table->id();
            $table->string('title', 150);
            $table->string('bar_text', 150)->nullable();
            $table->string('image')->nullable();   // path inside /public, empty = images/promo.jpg
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // the promo that is already live today
        DB::table('promos')->insert([
            'title'      => 'Retouch/Recolor Microbrows — ₱999 with FREE Lashes',
            'bar_text'   => 'Retouch/Recolor Microbrows only ₱999 with FREE Lashes',
            'image'      => null,
            'is_active'  => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('promos');
    }
};
