<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $upcoming = Appointment::with('service')
            ->where('user_id', $request->user()->id)
            ->whereDate('appointment_date', '>=', today())
            ->whereNotIn('status', ['cancelled', 'completed'])
            ->orderBy('appointment_date')
            ->orderBy('appointment_time')
            ->first();

        $appointments = Appointment::with(['service', 'staff.user'])
            ->where('user_id', $request->user()->id)
            ->latest('appointment_date')
            ->latest('appointment_time')
            ->paginate(8);

        return view('client.dashboard', compact('upcoming', 'appointments'));
    }
}
