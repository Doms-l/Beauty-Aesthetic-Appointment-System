<?php

namespace App\Support;

use App\Models\Appointment;
use App\Models\User;

/**
 * The red number on the navbar for staff and admin.
 *
 *  Staff: clients that need them  = today's visits still to do
 *         + bookings waiting for their confirmation
 *         + unassigned bookings they can claim.
 *  Admin: bookings still "pending" (new, not yet confirmed) from today on.
 */
class NavAlerts
{
    /** @return array{count:int, label:string, lines:array<int,string>} */
    public static function for(?User $user): array
    {
        $none = ['count' => 0, 'label' => '', 'lines' => []];

        if (! $user) {
            return $none;
        }

        if ($user->isAdmin()) {
            $new = Appointment::active()
                ->where('status', 'pending')
                ->whereDate('appointment_date', '>=', today())
                ->count();

            return [
                'count' => $new,
                'label' => $new === 0
                    ? 'No new appointments'
                    : "You have {$new} new " . ($new === 1 ? 'appointment' : 'appointments') . ' to review',
                'lines' => [],
            ];
        }

        if ($user->isStaff()) {
            $staff = $user->staffProfile;

            $today = $pending = 0;

            if ($staff) {
                $mine = fn () => Appointment::active()->where('staff_id', $staff->id);

                $today = $mine()
                    ->whereDate('appointment_date', today())
                    ->whereIn('status', ['pending', 'confirmed', 'rescheduled'])
                    ->count();

                // pending in the coming days (today's pending are already in $today)
                $pending = $mine()
                    ->where('status', 'pending')
                    ->whereDate('appointment_date', '>', today())
                    ->count();
            }

            $claim = Appointment::active()
                ->whereNull('staff_id')
                ->where('with_owner', false)
                ->whereIn('status', ['pending', 'confirmed', 'rescheduled'])
                ->whereDate('appointment_date', '>=', today())
                ->count();

            $lines = [];
            if ($today)   { $lines[] = $today . ' for today'; }
            if ($pending) { $lines[] = $pending . ' to confirm'; }
            if ($claim)   { $lines[] = $claim . ' open to claim'; }

            return [
                'count' => $today + $pending + $claim,
                'label' => $lines ? 'You have ' . implode(', ', $lines) : 'No clients waiting',
                'lines' => $lines,
            ];
        }

        return $none;
    }
}
