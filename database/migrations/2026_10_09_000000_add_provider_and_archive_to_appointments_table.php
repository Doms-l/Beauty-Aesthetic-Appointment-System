<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * with_owner  = the client asked to be served by the clinic owner
     * archived_at = admin archived the appointment (hidden from the
     *               active list and from every analytics panel)
     */
    public function up(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->boolean('with_owner')->default(false)->after('staff_id');
            $table->timestamp('archived_at')->nullable()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn(['with_owner', 'archived_at']);
        });
    }
};
