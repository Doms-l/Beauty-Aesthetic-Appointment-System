<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // every open booking of the client; a package booking is "open" until
        // its last session is done, even when its first day has already passed
        $open = Appointment::with(['service', 'sessions'])
            ->where('user_id', $request->user()->id)
            ->whereNotIn('status', ['cancelled', 'completed'])
            ->get();

        // the booking whose next visit comes first
        $upcoming = $open
            ->filter(fn (Appointment $a) => $a->next_visit_date && $a->next_visit_date->gte(today()))
            ->sortBy(fn (Appointment $a) => $a->next_visit_date->format('Y-m-d') . ' ' . $a->appointment_time)
            ->first();

        // no dated visit: show a started package that still waits for its next date
        $waitingPackage = $upcoming
            ? null
            : $open->first(fn (Appointment $a) => $a->is_package && $a->sessions_done >= 1 && $a->canLogSession());

        $appointments = Appointment::with(['service', 'staff.user'])
            ->where('user_id', $request->user()->id)
            ->latest('appointment_date')
            ->latest('appointment_time')
            ->paginate(8);

        return view('client.dashboard', compact('upcoming', 'waitingPackage', 'appointments'));
    }
}
