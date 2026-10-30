<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Support\StaffAppointments;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AppointmentController extends Controller
{
    /** My appointments, with simple filters. */
    public function index(Request $request)
    {
        $staff = $request->user()->staffProfile;

        $view   = $request->query('view', 'upcoming');
        $status = $request->query('status');

        $query = Appointment::with(['user', 'service', 'staff.user', 'sessions'])
            ->active()
            ->when($staff, fn ($q) => $q->where('staff_id', $staff->id), fn ($q) => $q->whereRaw('1 = 0'));

        match ($view) {
            'today'    => $query->whereDate('appointment_date', today()),
            'past'     => $query->whereDate('appointment_date', '<', today()),
            'all'      => null,
            default    => $query->whereDate('appointment_date', '>=', today()),
        };

        if (in_array($status, array_keys(StaffAppointments::TRANSITIONS), true)) {
            $query->where('status', $status);
        }

        $query->orderBy('appointment_date', $view === 'past' ? 'desc' : 'asc')
              ->orderBy('appointment_time');

        $appointments = $query->paginate(15)->withQueryString();

        return view('staff.appointments', [
            'staff'        => $staff,
            'appointments' => $appointments,
            'view'         => in_array($view, ['today', 'upcoming', 'past', 'all'], true) ? $view : 'upcoming',
            'status'       => $status,
        ]);
    }

    /** Change the status and / or the date and time of MY appointment. */
    public function update(Request $request, Appointment $appointment)
    {
        $staff = $request->user()->staffProfile;

        abort_unless(StaffAppointments::isMine($appointment, $staff), 403, 'This appointment is not assigned to you.');

        if (! StaffAppointments::canEdit($appointment, $staff)) {
            return back()->withErrors(['appointment' => 'A completed or cancelled appointment can only be changed by the admin.']);
        }

        $validated = $request->validate([
            'status' => ['required', Rule::in(StaffAppointments::allowedStatuses($appointment))],
            'appointment_date' => ['required', 'date_format:Y-m-d', 'after_or_equal:today'],
            'appointment_time' => ['required', 'date_format:H:i'],
        ]);

        $newStatus = $validated['status'];

        // A package completes itself after its last session ("Complete session")
        $appointment->loadMissing('service');

        if ($newStatus === 'completed'
            && $appointment->is_package
            && $appointment->sessions_done < (int) $appointment->service->sessions) {
            return back()->withErrors(['appointment' => 'This is a package. Use "Complete session" for each visit.']);
        }

        $rescheduled = $validated['appointment_date'] !== $appointment->appointment_date->format('Y-m-d')
            || $validated['appointment_time'] !== \Carbon\Carbon::parse($appointment->appointment_time)->format('H:i');

        // a new date or time with no other status chosen = rescheduled
        if ($rescheduled && $newStatus === $appointment->status && $newStatus !== 'rescheduled') {
            $newStatus = 'rescheduled';
        }

        if ($newStatus === 'completed') {
            $day = \Carbon\Carbon::createFromFormat('Y-m-d', $validated['appointment_date'])->startOfDay();

            if ($day->gt(today())) {
                return back()->withErrors(['appointment' => 'An appointment can only be completed on or after its day.']);
            }
        }

        $wasStatus = $appointment->status;

        $appointment->update([
            'status' => $newStatus,
            'appointment_date' => $validated['appointment_date'],
            'appointment_time' => $validated['appointment_time'] . ':00',
        ]);

        if ($newStatus === 'confirmed' && $wasStatus !== 'confirmed') {
            \App\Support\BookingNotifier::confirmed($appointment);
        }

        return back()->with('success', 'Appointment updated.');
    }

    /** Take an appointment nobody is assigned to yet. */
    public function claim(Request $request, Appointment $appointment)
    {
        $staff = $request->user()->staffProfile;

        abort_unless($staff, 403, 'Your account has no staff profile yet. Ask the admin.');

        if (! StaffAppointments::canClaim($appointment)) {
            return back()->withErrors(['appointment' => 'This appointment can no longer be taken.']);
        }

        $appointment->update(['staff_id' => $staff->id, 'with_owner' => false]);

        return back()->with('success', 'Appointment assigned to you.');
    }
}
