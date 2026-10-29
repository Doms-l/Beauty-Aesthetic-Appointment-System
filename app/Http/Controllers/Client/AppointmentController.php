<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Promo;
use App\Models\Service;
use App\Models\Staff;
use App\Models\User;
use App\Support\ClientTier;
use App\Support\PromoSlots;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

    public function create(Request $request)
    {
        // Get all available services (a promo service disappears once its
        // promo has ended or all of its slots are taken)
        $services = Service::where('is_available', true)
            ->whereNotIn('id', Promo::closedServiceIds())
            ->orderBy('category')
            ->orderBy('name')
            ->get();

        // Staff the client can choose from
        $staffMembers = Staff::with('user')
            ->where('is_available', true)
            ->get();

        // the promo the client came from: show its slots / end time
        $promo = $request->filled('promo')
            ? Promo::withBookedCount()->find($request->integer('promo'))
            : null;

        return view('appointments.create', compact('services', 'staffMembers', 'promo'));
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

        // Booking limits depend on the client's level (Member / Glow / VIP).
        // This protects the clinic from fake or spam bookings.
        $limitError = ClientTier::for($request->user())
            ->bookingError($request->user(), $validated['appointment_date']);

        if ($limitError) {
            return back()
                ->withErrors(['appointment_date' => $limitError])
                ->withInput();
        }

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

        // Limited promos: the slot is claimed and the booking saved in ONE
        // transaction, so the last slot can never be sold twice.
        DB::transaction(function () use ($request, $service, $staffId, $withOwner, $validated) {

            $promo = PromoSlots::claim($service, $request->user());

            Appointment::create([
                'user_id' => $request->user()->id,
                'service_id' => $service->id,
                'promo_id' => $promo?->id,
                'staff_id' => $staffId,
                'with_owner' => $withOwner,
                'appointment_date' => $validated['appointment_date'],
                'appointment_time' => $validated['appointment_time'],
                'notes' => $validated['notes'] ?? null,
                'status' => 'pending',
            ]);
        });

        return redirect()->route('client.appointments')
            ->with('success', 'Appointment request submitted. Please wait for clinic confirmation.');
    }

    public function cancel(Request $request, Appointment $appointment)
    {
        abort_unless($appointment->user_id === $request->user()->id, 403);

        if (in_array($appointment->status, ['completed', 'cancelled'], true)) {
            return back()->withErrors(['appointment' => 'This appointment can no longer be cancelled.']);
        }

        $appointment->update(['status' => 'cancelled', 'admin_saved_at' => null]);

        // Every cancellation by the client counts. At the limit the account
        // is banned and only an admin can unban it.
        $user = $request->user();
        $limit = (int) config('loyalty.cancel_ban_limit', 3);
        $strikes = $user->cancel_strikes + 1;

        if ($strikes >= $limit) {
            $user->forceFill([
                'cancel_strikes' => $strikes,
                'banned_at' => now(),
                'ban_reason' => "Cancelled {$limit} appointments",
            ])->save();

            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')
                ->withErrors(['email' => User::BAN_MESSAGE]);
        }

        $user->forceFill(['cancel_strikes' => $strikes])->save();

        $left = $limit - $strikes;

        return back()->with(
            'success',
            "Appointment cancelled. Warning: you have cancelled {$strikes} of {$limit} appointments. "
            . "{$left} more and your account will be banned."
        );
    }
}
