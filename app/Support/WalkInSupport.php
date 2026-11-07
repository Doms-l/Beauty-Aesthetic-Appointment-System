<?php

namespace App\Support;

use Illuminate\Support\Facades\Schema;

/**
 * Walk-in + "add service to a visit" need two migrations.
 * Until `php artisan migrate` has been run, the rest of the site must keep working,
 * so every place that uses the new columns/table asks this class first.
 */
class WalkInSupport
{
    private static ?bool $ready = null;

    public static function ready(): bool
    {
        if (self::$ready !== null) {
            return self::$ready;
        }

        try {
            return self::$ready = Schema::hasTable('visit_extra_services')
                && Schema::hasColumn('users', 'is_walk_in')
                && Schema::hasColumn('appointments', 'is_walk_in');
        } catch (\Throwable $e) {
            return self::$ready = false;
        }
    }
}
