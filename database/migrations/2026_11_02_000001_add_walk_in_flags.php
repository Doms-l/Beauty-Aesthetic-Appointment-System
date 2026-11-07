<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** Safe to run again: each column is added only if it is missing. */
return new class extends Migration {
    public function up(): void
    {
        // a booking the admin typed in for a customer who came to the clinic
        if (! Schema::hasColumn('appointments', 'is_walk_in')) {
            Schema::table('appointments', function (Blueprint $table) {
                $table->boolean('is_walk_in')->default(false)->after('with_owner');
            });
        }

        // a walk-in customer without an account (cannot log in, hidden from the client list)
        if (! Schema::hasColumn('users', 'is_walk_in')) {
            Schema::table('users', function (Blueprint $table) {
                $table->boolean('is_walk_in')->default(false)->after('role');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('appointments', 'is_walk_in')) {
            Schema::table('appointments', function (Blueprint $table) {
                $table->dropColumn('is_walk_in');
            });
        }

        if (Schema::hasColumn('users', 'is_walk_in')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('is_walk_in');
            });
        }
    }
};
