<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Closes gaps in session numbers (for example #2, #3, #4, #5 -> #1, #2, #3, #4)
 * so every package reads "Session 1 of N" ... "Session N of N".
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::transaction(function () {
            $ids = DB::table('appointment_sessions')->distinct()->pluck('appointment_id');

            foreach ($ids as $appointmentId) {
                $rows = DB::table('appointment_sessions')
                    ->where('appointment_id', $appointmentId)
                    ->orderBy('session_no')->orderBy('id')
                    ->get(['id', 'session_no']);

                // park on temporary numbers first so the unique index is never hit
                foreach ($rows as $row) {
                    DB::table('appointment_sessions')->where('id', $row->id)
                        ->update(['session_no' => $row->session_no + 1000]);
                }

                foreach ($rows->values() as $i => $row) {
                    DB::table('appointment_sessions')->where('id', $row->id)
                        ->update(['session_no' => $i + 1]);
                }
            }
        });
    }

    public function down(): void
    {
        // numbers cannot be restored to their old gaps
    }
};
