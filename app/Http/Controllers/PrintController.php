<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\AppointmentSession;
use App\Support\StaffAppointments;
use Illuminate\Http\Request;

/**
 * Printable papers:
 *  - receipt of a completed single-session service (half short bond)
 *  - receipt of one completed session of a package (half short bond)
 *  - package sheet with every finished session (short bond)
 *
 * Admin can print anything, staff only the appointments assigned to them.
 */
class PrintController extends Controller
{
    /** Receipt for a normal (single-session) service. */
    public function receipt(Request $request, Appointment $appointment)
    {
        $this->authorizePrint($request, $appointment);

        $appointment->load(['user', 'service', 'staff.user']);

        abort_if(
            $appointment->is_package,
            404,
            'This is a package. Print the receipt of a completed session instead.'
        );

        abort_unless(
            $appointment->status === 'completed',
            403,
            'A receipt can only be printed once the appointment is completed.'
        );

        // The receipt number is given once and then never changes
        if (! $appointment->receipt_number) {
            $appointment->receipt_number = 'MC-'
                . now()->format('Y')
                . '-'
                . str_pad((string) $appointment->id, 6, '0', STR_PAD_LEFT);
            $appointment->saveQuietly();
        }

        return view('print.receipt', [
            'appointment' => $appointment,
            'package'     => null,
            'receiptNo'   => $appointment->receipt_number,
            'serviceDate' => $appointment->appointment_date,
            'serviceTime' => $appointment->appointment_time,
            'staffName'   => $this->staffName($appointment),
            'issuedAt'    => now(),
        ]);
    }

    /** Receipt for ONE completed session of a package ("Session 2 of 4"). */
    public function sessionReceipt(Request $request, AppointmentSession $session)
    {
        $appointment = $session->appointment()->with(['user', 'service', 'staff.user'])->firstOrFail();

        $this->authorizePrint($request, $appointment);

        if (! $session->receipt_number) {
            $session->receipt_number = 'MCP-'
                . now()->format('Y')
                . '-'
                . str_pad((string) $session->id, 6, '0', STR_PAD_LEFT);
            $session->save();
        }

        return view('print.receipt', [
            'appointment' => $appointment,
            'package'     => [
                'sessions'   => (int) $appointment->service->sessions,
                'session_no' => $session->session_no,
            ],
            'receiptNo'   => $session->receipt_number,
            'serviceDate' => $session->session_date,
            'serviceTime' => $session->session_time,
            'staffName'   => $session->staff_label,
            'issuedAt'    => now(),
        ]);
    }

    /** Clients Package Sessions sheet, filled from the finished sessions. */
    public function package(Request $request, Appointment $appointment)
    {
        $this->authorizePrint($request, $appointment);

        $appointment->load(['user', 'service', 'staff.user', 'sessions.staff.user', 'sessions.appointment']);

        abort_unless(
            $appointment->is_package,
            404,
            'This appointment is not a package.'
        );

        $sessions = $appointment->sessions;

        return view('print.package', [
            'appointment' => $appointment,
            'total'       => (int) $appointment->service->sessions,
            'sessions'    => $sessions,
            'staffName'   => $this->staffName($appointment),
            'startDate'   => $sessions->first()?->session_date ?? $appointment->appointment_date,
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
