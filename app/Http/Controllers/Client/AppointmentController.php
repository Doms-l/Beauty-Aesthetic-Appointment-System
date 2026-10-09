<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Service;
use App\Models\Staff;
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
        // Get all available services
        $services = Service::where('is_available', true)
            ->orderBy('category')
            ->orderBy('name')
            ->get();

        // Staff the client can choose from
        $staffMembers = Staff::with('user')
            ->where('is_available', true)
            ->get();

        return view('appointments.create', compact('services', 'staffMembers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_id' => ['required', 'integer', 'exists:services,id'],
            'appointment_date' => ['required', 'date', 'after_or_equal:today'],
            'appointment_time' => ['required', 'date_format:H:i'],
            'provider' => ['nullable', 'string', 'max:20'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $service = Service::whereKey($validated['service_id'])
            ->where('is_available', true)
            ->firstOrFail();

        // Who will do the service?
        //   "any"    = no preference
        //   "owner"  = the clinic owner
        //   a number = the id of a registered staff member
        $provider = $validated['provider'] ?? 'any';
        $staffId = null;
        $withOwner = false;

        if ($provider === 'owner') {

            $withOwner = true;

        } elseif (ctype_digit($provider)) {

            $staffId = Staff::whereKey((int) $provider)
                ->where('is_available', true)
                ->value('id');

            if (!$staffId) {
                return back()
                    ->withErrors(['provider' => 'The selected staff member is not available.'])
                    ->withInput();
            }
        }

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
            'staff_id' => $staffId,
            'with_owner' => $withOwner,
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
