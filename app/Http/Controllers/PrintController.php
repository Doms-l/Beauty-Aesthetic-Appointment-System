<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Support\PackageSessions;
use App\Support\StaffAppointments;
use Illuminate\Http\Request;

/**
 * Printable papers for an appointment:
 *  - receipt (half short bond), only once the appointment is Completed
 *  - package sheet (short bond), for services with more than one session
 *
 * Admin can print any appointment, staff only the ones assigned to them.
 */
class PrintController extends Controller
{
    public function receipt(Request $request, Appointment $appointment)
    {
        $this->authorizePrint($request, $appointment);

        abort_unless(
            $appointment->status === 'completed',
            403,
            'A receipt can only be printed once the appointment is completed.'
        );

        $appointment->load(['user', 'service', 'staff.user']);

        // The receipt number is given once and then never changes
        if (! $appointment->receipt_number) {
            $appointment->receipt_number = 'MC-'
                . now()->format('Y')
                . '-'
                . str_pad((string) $appointment->id, 6, '0', STR_PAD_LEFT);
            $appointment->saveQuietly();
        }

        $package = PackageSessions::for($appointment);

        return view('print.receipt', [
            'appointment' => $appointment,
            'package'     => $package,
            'staffName'   => $this->staffName($appointment),
            'issuedAt'    => now(),
        ]);
    }

    public function package(Request $request, Appointment $appointment)
    {
        $this->authorizePrint($request, $appointment);

        $appointment->load(['user', 'service', 'staff.user']);

        $package = PackageSessions::for($appointment);

        abort_unless(
            $package !== null,
            404,
            'This appointment is not part of a package.'
        );

        $bookings = $package['bookings'];

        return view('print.package', [
            'appointment' => $appointment,
            'package'     => $package,
            'staffName'   => $this->staffName($appointment),
            'startDate'   => optional($bookings->first())->appointment_date,
            'rowStaff'    => fn (Appointment $a) => $this->staffName($a),
        ]);
    }

    private function authorizePrint(Request $request, Appointment $appointment): void
    {
        $user = $request->user();

        if ($user->isAdmin()) {
            return;
        }

        abort_unless(
            $user->isStaff() && StaffAppointments::isMine($appointment, $user->staffProfile),
            403,
            'This appointment is not assigned to you.'
        );
    }

    /** Name written on the paper; empty when nobody is assigned yet. */
    private function staffName(Appointment $appointment): string
    {
        $label = $appointment->provider_label;

        return $label === 'Any available' ? '' : $label;
    }
}
