<?php

namespace App\Support;

use App\Models\Appointment;
use App\Models\Staff;
use Carbon\Carbon;

/**
 * What a staff member may do with an appointment.
 *
 * - They manage appointments assigned to them.
 * - They can take ("claim") an appointment nobody is assigned to yet
 *   (the client chose "No preference").
 * - Appointments the client asked the owner for, or that belong to another
 *   staff member, are admin-only.
 * - A finished appointment (completed / cancelled) is locked; only the
 *   admin can reopen it.
 * - Status moves forward only (see TRANSITIONS).
 */
class StaffAppointments
{
    /** status => statuses it may be changed to (itself is always allowed) */
    public const TRANSITIONS = [
        'pending'     => ['confirmed', 'rescheduled', 'cancelled'],
        'confirmed'   => ['rescheduled', 'completed', 'cancelled'],
        'rescheduled' => ['confirmed', 'completed', 'cancelled'],
        'completed'   => [],
        'cancelled'   => [],
    ];

    public static function isMine(Appointment $appointment, ?Staff $staff): bool
    {
        return $staff && (int) $appointment->staff_id === (int) $staff->id;
    }

    public static function canClaim(Appointment $appointment): bool
    {
        return $appointment->staff_id === null
            && ! $appointment->with_owner
            && $appointment->archived_at === null
            && in_array($appointment->status, ['pending', 'confirmed', 'rescheduled'], true);
    }

    public static function isLocked(Appointment $appointment): bool
    {
        return in_array($appointment->status, ['completed', 'cancelled'], true);
    }

    public static function canEdit(Appointment $appointment, ?Staff $staff): bool
    {
        return self::isMine($appointment, $staff)
            && $appointment->archived_at === null
            && ! self::isLocked($appointment);
    }

    /** @return string[] statuses the staff member may pick (current one first) */
    public static function allowedStatuses(Appointment $appointment): array
    {
        return array_merge([$appointment->status], self::TRANSITIONS[$appointment->status] ?? []);
    }

    /** "Complete" only once the appointment day has come. */
    public static function canComplete(Appointment $appointment): bool
    {
        return $appointment->appointment_date->lte(Carbon::today());
    }

    /** Everything the details / manage window needs for one row. */
    public static function payload(Appointment $appointment, ?Staff $staff): array
    {
        $tier = ClientTier::for($appointment->user);

        $statuses = [];
        foreach (self::allowedStatuses($appointment) as $status) {
            if ($status === 'completed' && ! self::canComplete($appointment)) {
                continue; // cannot complete a future appointment
            }
            $statuses[] = ['value' => $status, 'label' => ucfirst($status)];
        }

        return [
            'client'     => $appointment->user->full_name,
            'email'      => $appointment->user->email,
            'phone'      => $appointment->user->phone ?: '-',
            'level'      => $tier->name() . ' (' . $tier->completed . ' completed ' . ($tier->completed === 1 ? 'visit' : 'visits') . ')',
            'service'    => $appointment->service->name,
            'category'   => $appointment->service->category ?: '-',
            'price'      => $appointment->service->display_price,
            'duration'   => $appointment->service->duration_minutes ? $appointment->service->duration_minutes . ' minutes' : '-',
            'dateLabel'  => $appointment->appointment_date->format('l, M d, Y'),
            'timeLabel'  => Carbon::parse($appointment->appointment_time)->format('h:i A'),
            'booked'     => $appointment->created_at->format('M d, Y, h:i A'),
            'provider'   => $appointment->provider_label . ($appointment->with_owner && ! $appointment->staff_id ? ' (requested)' : ''),
            'status'     => ucfirst($appointment->status),
            'notes'      => trim((string) $appointment->notes),
            'date'       => $appointment->appointment_date->format('Y-m-d'),
            'time'       => Carbon::parse($appointment->appointment_time)->format('H:i'),
            'statuses'   => $statuses,
            'canEdit'    => self::canEdit($appointment, $staff),
            'canClaim'   => self::canClaim($appointment),
            'locked'     => self::isLocked($appointment),
            'updateUrl'  => route('staff.appointments.update', $appointment),
            'claimUrl'   => route('staff.appointments.claim', $appointment),
        ];
    }
}
