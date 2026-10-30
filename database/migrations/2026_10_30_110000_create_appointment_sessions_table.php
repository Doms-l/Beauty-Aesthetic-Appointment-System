<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // One row for every finished visit of a package booking
        // (e.g. session 1, 2, 3, 4 of the Free Stemcell Facial).
        Schema::create('appointment_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('appointment_id')->constrained()->cascadeOnDelete();
            $table->unsignedSmallInteger('session_no');
            $table->date('session_date');
            $table->time('session_time');
            $table->foreignId('staff_id')->nullable()->constrained('staff')->nullOnDelete();
            $table->string('receipt_number', 30)->nullable()->unique();
            $table->timestamps();

            $table->unique(['appointment_id', 'session_no']);
        });

        // Bookings of a package that were already marked Completed before this
        // update were ONE visit each: that visit becomes session 1, and the
        // booking stays open (Confirmed) until all its sessions are done.
        $packageIds = DB::table('services')->where('sessions', '>', 1)->pluck('id');

        $finished = DB::table('appointments')
            ->whereIn('service_id', $packageIds)
            ->where('status', 'completed')
            ->get();

        foreach ($finished as $appointment) {
            DB::table('appointment_sessions')->insert([
                'appointment_id' => $appointment->id,
                'session_no'     => 1,
                'session_date'   => $appointment->appointment_date,
                'session_time'   => $appointment->appointment_time,
                'staff_id'       => $appointment->staff_id,
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);

            DB::table('appointments')
                ->where('id', $appointment->id)
                ->update(['status' => 'confirmed']);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('appointment_sessions');
    }
};
