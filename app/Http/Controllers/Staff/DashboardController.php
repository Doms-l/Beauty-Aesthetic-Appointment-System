<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Staff;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $staff = $this->staffOf($request);

        $with = ['user', 'service', 'staff.user'];

        $mine = fn () => Appointment::with($with)
            ->active()
            ->when($staff, fn ($q) => $q->where('staff_id', $staff->id), fn ($q) => $q->whereRaw('1 = 0'));

        // today (not cancelled), earliest first
        $todayList = $mine()
            ->whereDate('appointment_date', today())
            ->where('status', '!=', 'cancelled')
            ->orderBy('appointment_time')
            ->get();

        // coming days
        $upcomingList = $mine()
            ->whereDate('appointment_date', '>', today())
            ->whereIn('status', ['pending', 'confirmed', 'rescheduled'])
            ->orderBy('appointment_date')
            ->orderBy('appointment_time')
            ->take(15)
            ->get();

        // waiting for the staff member to confirm
        $pendingList = $mine()
            ->where('status', 'pending')
            ->whereDate('appointment_date', '>=', today())
            ->orderBy('appointment_date')
            ->orderBy('appointment_time')
            ->get();

        // nobody assigned yet ("No preference"): any staff member may take them
        $claimList = Appointment::with($with)
            ->active()
            ->whereNull('staff_id')
            ->where('with_owner', false)
            ->whereIn('status', ['pending', 'confirmed', 'rescheduled'])
            ->whereDate('appointment_date', '>=', today())
            ->orderBy('appointment_date')
            ->orderBy('appointment_time')
            ->take(15)
            ->get();

        $stats = [
            'today'     => $todayList->count(),
            'upcoming'  => $mine()->whereDate('appointment_date', '>', today())
                                  ->whereIn('status', ['pending', 'confirmed', 'rescheduled'])->count(),
            'pending'   => $pendingList->count(),
            'completed' => $mine()->where('status', 'completed')
                                  ->whereYear('appointment_date', now()->year)
                                  ->whereMonth('appointment_date', now()->month)->count(),
        ];

        return view('staff.dashboard', compact(
            'staff', 'todayList', 'upcomingList', 'pendingList', 'claimList', 'stats'
        ));
    }

    private function staffOf(Request $request): ?Staff
    {
        return $request->user()->staffProfile;
    }
}
