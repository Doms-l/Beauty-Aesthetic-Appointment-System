<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Service;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $appointments = Appointment::with(['service', 'staff.user'])
            ->where('user_id', $request->user()->id)
            ->orderByDesc('appointment_date')
            ->orderByDesc('appointment_time')
            ->paginate(10);

        return view('appointments.index', compact('appointments'));
    }

    public function create()
    {
        $services = Service::where('is_available', true)
            ->orderBy('name')
            ->get();

        return view('appointments.create', compact('services'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_id' => ['required', 'integer', 'exists:services,id'],
            'appointment_date' => ['required', 'date', 'after_or_equal:today'],
            'appointment_time' => ['required', 'date_format:H:i'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $service = Service::whereKey($validated['service_id'])
            ->where('is_available', true)
            ->firstOrFail();

        // Prevent the same client from double-booking the same time.
        $alreadyBooked = Appointment::where('user_id', $request->user()->id)
            ->whereDate('appointment_date', $validated['appointment_date'])
            ->whereTime('appointment_time', $validated['appointment_time'])
            ->whereNotIn('status', ['cancelled'])
            ->exists();

        if ($alreadyBooked) {
            return back()
                ->withErrors(['appointment_time' => 'You already have an appointment at this date and time.'])
                ->withInput();
        }

        Appointment::create([
            'user_id' => $request->user()->id,
            'service_id' => $service->id,
            'appointment_date' => $validated['appointment_date'],
            'appointment_time' => $validated['appointment_time'],
            'notes' => $validated['notes'] ?? null,
            'status' => 'pending',
        ]);

        return redirect()->route('client.appointments')
            ->with('success', 'Appointment request submitted. Please wait for clinic confirmation.');
    }

    public function cancel(Request $request, Appointment $appointment)
    {
        abort_unless($appointment->user_id === $request->user()->id, 403);

        if (in_array($appointment->status, ['completed', 'cancelled'], true)) {
            return back()->withErrors(['appointment' => 'This appointment can no longer be cancelled.']);
        }

        $appointment->update(['status' => 'cancelled']);

        return back()->with('success', 'Appointment cancelled successfully.');
    }
}
