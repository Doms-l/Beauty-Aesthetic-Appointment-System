<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The social accounts / contact details shown in the website footer.
     * Managed by the admin under "Social accounts".
     */
    public function up(): void
    {
        Schema::create('social_links', function (Blueprint $table) {
            $table->id();
            $table->string('platform', 30);          // facebook, instagram, telegram, phone, ...
            $table->string('label', 100);            // text shown next to the logo
            $table->string('url', 255);              // link, phone number or email address
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // the two contacts that are already in the footer today
        DB::table('social_links')->insert([
            [
                'platform'   => 'facebook',
                'label'      => 'Macayla Cares',
                'url'        => 'https://www.facebook.com/macaylaanjeaneath.raejell',
                'sort_order' => 1,
                'is_active'  => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'platform'   => 'phone',
                'label'      => '09155168312',
                'url'        => '09155168312',
                'sort_order' => 2,
                'is_active'  => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('social_links');
    }
};
