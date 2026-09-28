<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Staff;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index()
    {
        $appointments = Appointment::with(['user', 'service', 'staff.user'])
            ->latest('appointment_date')
            ->latest('appointment_time')
            ->paginate(15);

        $staff = Staff::with('user')->where('is_available', true)->get();

        return view('admin.appointments', compact('appointments', 'staff'));
    }

    public function update(Request $request, Appointment $appointment)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,confirmed,completed,cancelled,rescheduled'],
            'staff_id' => ['nullable', 'exists:staff,id'],
        ]);

        $appointment->update($validated);

        return back()->with('success', 'Appointment updated successfully.');
    }
}
