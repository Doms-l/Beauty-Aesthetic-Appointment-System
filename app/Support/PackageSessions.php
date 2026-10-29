<?php

namespace App\Support;

use App\Models\Appointment;

/**
 * Groups a client's bookings of a package service into packages.
 *
 * A client's non-cancelled, non-archived bookings of the same service are
 * sorted by date and cut into groups of "sessions" (e.g. 4):
 * bookings 1-4 are package 1, bookings 5-8 are package 2, and so on.
 */
class PackageSessions
{
    /**
     * @return array{
     *   sessions:int,
     *   package:int,
     *   session_no:int,
     *   bookings:\Illuminate\Support\Collection
     * }|null  null when this appointment is not part of a counted package
     */
    public static function for(Appointment $appointment): ?array
    {
        $appointment->loadMissing('service');

        $sessions = (int) ($appointment->service->sessions ?? 1);

        if ($sessions < 2) {
            return null;
        }

        $all = Appointment::with(['staff.user'])
            ->counted()
            ->where('user_id', $appointment->user_id)
            ->where('service_id', $appointment->service_id)
            ->orderBy('appointment_date')
            ->orderBy('appointment_time')
            ->orderBy('id')
            ->get();

        $position = $all->search(fn (Appointment $a) => $a->id === $appointment->id);

        if ($position === false) {
            return null;
        }

        $package = intdiv($position, $sessions);

        return [
            'sessions'   => $sessions,
            'package'    => $package + 1,
            'session_no' => ($position % $sessions) + 1,
            'bookings'   => $all->slice($package * $sessions, $sessions)->values(),
        ];
    }
}
