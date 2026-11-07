<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\AppointmentExtra;
use App\Models\Service;
use App\Models\Staff;
use App\Models\User;
use App\Support\WalkInSupport;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        // ?archived=1 shows the archive instead of the active list
        $showArchived = $request->boolean('archived');

        // ?date=YYYY-MM-DD shows only the bookings on that day
        $selectedDate = null;
        if ($request->filled('date')) {
            try {
                $selectedDate = Carbon::createFromFormat('Y-m-d', $request->input('date'))->toDateString();
            } catch (\Throwable $e) {
                $selectedDate = null; // ignore a bad date instead of crashing
            }
        }

        // ?status=pending shows only the new requests waiting for review
        $pendingOnly = $request->input('status') === 'pending' && ! $showArchived;

        // ?view=history shows only finished (Completed) or already-past bookings
        $historyOnly = $request->input('view') === 'history' && ! $showArchived;

        $appointments = Appointment::with(array_merge(
                ['user', 'service', 'staff.user', 'sessions'],
                WalkInSupport::ready() ? ['extras.service'] : []
            ))
            ->when(
                $showArchived,
                fn ($q) => $q->whereNotNull('archived_at'),
                fn ($q) => $q->whereNull('archived_at')
            )
            ->when($selectedDate, fn ($q) => $q->whereDate('appointment_date', $selectedDate))
            ->when($pendingOnly, fn ($q) => $q->where('status', 'pending')->whereDate('appointment_date', '>=', today()))
            ->when($historyOnly, fn ($q) => $q->where(
                fn ($w) => $w->where('status', 'completed')->orWhereDate('appointment_date', '<', today())
            ))
            ->latest('appointment_date')
            ->latest('appointment_time')
            ->paginate(15)
            ->withQueryString();

        $archivedCount = Appointment::whereNotNull('archived_at')->count();

        // number shown on the "Completed & past" button
        $historyCount = Appointment::whereNull('archived_at')
            ->where(fn ($w) => $w->where('status', 'completed')->orWhereDate('appointment_date', '<', today()))
            ->count();

        // same number as the red badge in the navbar
        $newCount = Appointment::active()
            ->where('status', 'pending')
            ->whereDate('appointment_date', '>=', today())
            ->count();

        $staff = Staff::with('user')->where('is_available', true)->get();

        // Walk-in / Add service only work after `php artisan migrate`
        $walkInReady = WalkInSupport::ready();

        // services the admin can add to a visit (single-session only)
        $extraServices = $walkInReady
            ? Service::where('is_available', true)
                ->where(fn ($q) => $q->whereNull('sessions')->orWhere('sessions', '<=', 1))
                ->orderBy('category')
                ->orderBy('name')
                ->get(['id', 'name', 'category', 'price'])
            : collect();

        // registered clients for the walk-in form
        $walkInClients = $walkInReady
            ? User::where('role', 'client')
                ->where('is_walk_in', false)
                ->orderBy('first_name')
                ->orderBy('last_name')
                ->get(['id', 'first_name', 'last_name', 'phone'])
            : collect();

        return view('admin.appointments', compact(
            'appointments',
            'staff',
            'showArchived',
            'archivedCount',
            'selectedDate',
            'pendingOnly',
            'historyOnly',
            'historyCount',
            'newCount',
            'extraServices',
            'walkInClients',
            'walkInReady'
        ));
    }

    public function update(Request $request, Appointment $appointment)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,confirmed,completed,cancelled,rescheduled'],
            'staff_id' => ['nullable', 'exists:staff,id'],
        ]);

        // A package is finished by completing its sessions one by one,
        // not by picking "Completed" for the whole booking.
        $appointment->loadMissing('service');

        if ($validated['status'] === 'completed'
            && $appointment->status !== 'completed'
            && $appointment->is_package
            && $appointment->sessions_done < (int) $appointment->service->sessions) {
            return back()->withErrors([
                'appointment' => 'This is a package. Use "Complete session" for each visit; the booking completes itself after the last session.',
            ]);
        }

        // Assigning a staff member replaces the "Owner" request
        if (!empty($validated['staff_id'])) {
            $validated['with_owner'] = false;
        }

        // clicking Save marks this row (and only this row) as saved
        $validated['admin_saved_at'] = now();

        $wasStatus = $appointment->status;

        $appointment->update($validated);

        if ($validated['status'] === 'confirmed' && $wasStatus !== 'confirmed') {
            \App\Support\BookingNotifier::confirmed($appointment);
        }

        $message = $appointment->status === 'confirmed'
            ? 'Appointment confirmed successfully.'
            : 'Appointment updated successfully.';

        return back()->with('success', $message);
    }

    /**
     * WALK-IN: the admin books for a customer who is at the clinic.
     * Works for a registered client OR for someone without an account.
     * The client level limits do not apply to the admin.
     */
    public function storeWalkIn(Request $request)
    {
        if (! WalkInSupport::ready()) {
            return back()->withErrors(['appointment' => 'Please run "php artisan migrate" first to turn on this feature.']);
        }

        $validated = $request->validate([
            'client_mode' => ['required', 'in:existing,new'],
            'user_id'     => ['required_if:client_mode,existing', 'nullable', 'integer'],
            'first_name'  => ['required_if:client_mode,new', 'nullable', 'string', 'max:100'],
            'last_name'   => ['required_if:client_mode,new', 'nullable', 'string', 'max:100'],
            'phone'       => ['nullable', 'digits:11'],
            'service_id'  => ['required', 'integer', 'exists:services,id'],
            'provider'    => ['nullable', 'string', 'max:20'],
            'appointment_date' => ['required', 'date', 'after_or_equal:today'],
            'appointment_time' => ['required', 'date_format:H:i'],
            'status'      => ['required', 'in:pending,confirmed,completed'],
            'notes'       => ['nullable', 'string', 'max:1000'],
        ], [
            'user_id.required_if'    => 'Please choose the client.',
            'first_name.required_if' => 'Please type the first name.',
            'last_name.required_if'  => 'Please type the last name.',
            'phone.digits'           => 'Phone number must contain exactly 11 digits.',
        ]);

        $service = Service::findOrFail($validated['service_id']);

        // a package is finished session by session, not in one click
        if ($service->is_package && $validated['status'] === 'completed') {
            return back()->withInput()->withErrors([
                'walk_in' => 'A package cannot be completed at once. Save the walk-in as Confirmed, then record each session from the list.',
            ]);
        }

        // Who will do the service?
        $provider  = $validated['provider'] ?? 'any';
        $staffId   = null;
        $withOwner = false;

        if ($provider === 'owner') {
            $withOwner = true;
        } elseif (ctype_digit($provider)) {
            $staffId = Staff::whereKey((int) $provider)->value('id');

            if (! $staffId) {
                return back()->withInput()->withErrors(['walk_in' => 'The selected staff member was not found.']);
            }
        }

        $appointment = DB::transaction(function () use ($validated, $service, $staffId, $withOwner) {

            if ($validated['client_mode'] === 'existing') {
                $client = User::where('role', 'client')->whereKey($validated['user_id'])->first();

                if (! $client) {
                    return null;
                }
            } else {
                // a customer without an account: a hidden, login-less record
                $client = new User();
                $client->forceFill([
                    'first_name'        => trim($validated['first_name']),
                    'last_name'         => trim($validated['last_name']),
                    'email'             => 'walkin-' . Str::uuid() . '@walkin.invalid',
                    'phone'             => $validated['phone'] ?? null,
                    'role'              => 'client',
                    'is_walk_in'        => true,
                    'email_verified_at' => now(),
                    'password'          => Hash::make(Str::random(40)),
                ])->save();
            }

            return Appointment::create([
                'user_id'          => $client->id,
                'service_id'       => $service->id,
                'staff_id'         => $staffId,
                'with_owner'       => $withOwner,
                'is_walk_in'       => true,
                'appointment_date' => $validated['appointment_date'],
                'appointment_time' => $validated['appointment_time'],
                'notes'            => $validated['notes'] ?? null,
                'status'           => $validated['status'],
                'admin_saved_at'   => now(),
            ]);
        });

        if (! $appointment) {
            return back()->withInput()->withErrors(['walk_in' => 'That client was not found.']);
        }

        return redirect()
            ->route('admin.appointments', ['date' => $appointment->appointment_date->toDateString()])
            ->with('success', 'Walk-in added successfully.');
    }

    /** Add one more service to an existing visit (same client, day, time). */
    public function addExtra(Request $request, Appointment $appointment)
    {
        if (! WalkInSupport::ready()) {
            return back()->withErrors(['appointment' => 'Please run "php artisan migrate" first to turn on this feature.']);
        }

        $validated = $request->validate([
            'service_id' => ['required', 'integer', 'exists:services,id'],
        ]);

        if (! $appointment->canChangeExtras()) {
            return back()->withErrors([
                'appointment' => $appointment->is_package
                    ? 'A package booking cannot have an added service. Add the service as a walk-in instead.'
                    : 'A service can only be added while the appointment is pending, confirmed or rescheduled.',
            ]);
        }

        $service = Service::whereKey($validated['service_id'])->where('is_available', true)->first();

        if (! $service || $service->is_package) {
            return back()->withErrors(['appointment' => 'Please choose a single-session service.']);
        }

        if ((int) $service->id === (int) $appointment->service_id
            || $appointment->extras()->where('service_id', $service->id)->exists()) {
            return back()->withErrors(['appointment' => $service->name . ' is already part of this visit.']);
        }

        AppointmentExtra::create([
            'appointment_id' => $appointment->id,
            'service_id'     => $service->id,
            'price'          => $service->price,
        ]);

        return back()->with('success', $service->name . ' was added to this visit.');
    }

    /** Take an added service off the visit. */
    public function removeExtra(AppointmentExtra $extra)
    {
        if (! WalkInSupport::ready()) {
            return back()->withErrors(['appointment' => 'Please run "php artisan migrate" first to turn on this feature.']);
        }

        $appointment = $extra->appointment;

        if (! $appointment || ! $appointment->canChangeExtras()) {
            return back()->withErrors(['appointment' => 'This appointment is closed, so its services can no longer change.']);
        }

        $extra->delete();

        return back()->with('success', 'Service removed from this visit.');
    }

    /** Take the "Saved" mark off a row (it goes back to red "Unsaved"). */
    public function unsave(Appointment $appointment)
    {
        $appointment->update(['admin_saved_at' => null]);

        return back()->with('success', 'Appointment marked as unsaved.');
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
