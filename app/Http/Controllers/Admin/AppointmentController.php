<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Staff;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        // ?archived=1 shows the archive instead of the active list
        $showArchived = $request->boolean('archived');

        $appointments = Appointment::with(['user', 'service', 'staff.user'])
            ->when(
                $showArchived,
                fn ($q) => $q->whereNotNull('archived_at'),
                fn ($q) => $q->whereNull('archived_at')
            )
            ->latest('appointment_date')
            ->latest('appointment_time')
            ->paginate(15)
            ->withQueryString();

        $archivedCount = Appointment::whereNotNull('archived_at')->count();

        $staff = Staff::with('user')->where('is_available', true)->get();

        return view('admin.appointments', compact(
            'appointments',
            'staff',
            'showArchived',
            'archivedCount'
        ));
    }

    public function update(Request $request, Appointment $appointment)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,confirmed,completed,cancelled,rescheduled'],
            'staff_id' => ['nullable', 'exists:staff,id'],
        ]);

        // Assigning a staff member replaces the "Owner" request
        if (!empty($validated['staff_id'])) {
            $validated['with_owner'] = false;
        }

        $appointment->update($validated);

        return back()->with('success', 'Appointment updated successfully.');
    }

    /**
     * Archive a CANCELLED appointment.
     * It disappears from the list and from every analytics panel.
     */
    public function archive(Appointment $appointment)
    {
        if ($appointment->status !== 'cancelled') {
            return back()->withErrors([
                'appointment' => 'Only cancelled appointments can be archived.',
            ]);
        }

        $appointment->update(['archived_at' => now()]);

        return back()->with('success', 'Appointment archived.');
    }

    public function restore(Appointment $appointment)
    {
        $appointment->update(['archived_at' => null]);

        return back()->with('success', 'Appointment restored.');
    }
}
