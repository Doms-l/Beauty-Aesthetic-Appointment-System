<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\AppointmentSession;
use App\Support\StaffAppointments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Logs the visits of a package booking.
 *
 * One booking = the whole package (e.g. 4 sessions). Every time the client
 * comes in, staff / admin press "Complete session". After the last session the
 * booking becomes Completed by itself.
 */
class AppointmentSessionController extends Controller
{
    /** Log the next visit of a package booking. */
    public function store(Request $request, Appointment $appointment)
    {
        $user = $request->user();

        if (! $user->isAdmin()) {
            abort_unless(
                $user->isStaff() && StaffAppointments::canEdit($appointment, $user->staffProfile),
                403,
                'This appointment is not assigned to you.'
            );
        }

        $appointment->load('service');

        if (! $appointment->canLogSession()) {
            return back()->withErrors([
                'appointment' => 'No more sessions can be completed on this booking.',
            ]);
        }

        $validated = $request->validate([
            'session_date' => ['required', 'date_format:Y-m-d', 'before_or_equal:today'],
        ]);

        $error = null;

        DB::transaction(function () use ($appointment, $validated, $user, &$error) {

            // lock the booking so two clicks cannot create the same session number
            $locked = Appointment::whereKey($appointment->id)->lockForUpdate()->first();
            $done   = $locked->sessions()->count();
            $total  = (int) $appointment->service->sessions;

            if ($done >= $total) {
                $error = 'All sessions of this package are already completed.';
                return;
            }

            $last = $locked->sessions()->latest('session_no')->first();

            if ($last && $validated['session_date'] < $last->session_date->format('Y-m-d')) {
                $error = 'Session ' . ($done + 1) . ' cannot be dated before session ' . $done . ' ('
                    . $last->session_date->format('M d, Y') . ').';
                return;
            }

            // today = the time right now, an earlier day = the time the booking was made for
            $time = $validated['session_date'] === today()->format('Y-m-d')
                ? now()->format('H:i:s')
                : $locked->appointment_time;

            $staffId = $user->isStaff() && $user->staffProfile
                ? $user->staffProfile->id
                : $locked->staff_id;

            // highest number + 1 (not count + 1), so an undo or a gap can never repeat a number
            $nextNo = (int) $locked->sessions()->max('session_no') + 1;

            $locked->sessions()->create([
                'session_no'   => $nextNo,
                'session_date' => $validated['session_date'],
                'session_time' => $time,
                'staff_id'     => $staffId,
            ]);

            // the planned day has been used up
            $locked->update(['next_session_date' => null]);

            if ($done + 1 >= $total) {
                $locked->update(['status' => 'completed']);
            } elseif (in_array($locked->status, ['pending', 'rescheduled'], true)) {
                $locked->update(['status' => 'confirmed']);
            }
        });

        if ($error) {
            return back()->withErrors(['appointment' => $error]);
        }

        $appointment->refresh();

        return back()->with(
            'success',
            'Session ' . $appointment->sessions_done . ' of ' . $appointment->service->sessions . ' completed.'
        );
    }

    /** Set (or clear) the planned day of the next visit of a package booking. */
    public function schedule(Request $request, Appointment $appointment)
    {
        $user = $request->user();

        if (! $user->isAdmin()) {
            abort_unless(
                $user->isStaff() && StaffAppointments::canEdit($appointment, $user->staffProfile),
                403,
                'This appointment is not assigned to you.'
            );
        }

        $appointment->load('service');

        if (! $appointment->canLogSession()) {
            return back()->withErrors(['appointment' => 'This package has no session left to schedule.']);
        }

        $validated = $request->validate([
            'next_session_date' => ['nullable', 'date_format:Y-m-d', 'after_or_equal:today'],
        ]);

        $last = $appointment->sessions()->latest('session_no')->first();

        if (! empty($validated['next_session_date'])
            && $last
            && $validated['next_session_date'] < $last->session_date->format('Y-m-d')) {
            return back()->withErrors([
                'appointment' => 'The next visit cannot be before the last session (' . $last->session_date->format('M d, Y') . ').',
            ]);
        }

        $appointment->update(['next_session_date' => $validated['next_session_date'] ?? null]);

        return back()->with('success', empty($validated['next_session_date'])
            ? 'Next visit date cleared.'
            : 'Next visit set to ' . \Carbon\Carbon::parse($validated['next_session_date'])->format('M d, Y') . '.');
    }

    /** Admin only: take the last logged visit back (a mistake). */
    public function undo(Request $request, Appointment $appointment)
    {
        abort_unless($request->user()->isAdmin(), 403, 'Only the admin can undo a session.');

        $last = $appointment->sessions()->latest('session_no')->first();

        if (! $last) {
            return back()->withErrors(['appointment' => 'There is no completed session to undo.']);
        }

        DB::transaction(function () use ($appointment, $last) {
            $last->delete();

            // a booking that was finished by its last session is open again
            if ($appointment->status === 'completed') {
                $appointment->update(['status' => 'confirmed']);
            }
        });

        return back()->with('success', 'Session ' . $last->session_no . ' was undone.');
    }
}
