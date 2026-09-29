<?php 
 
namespace App\Http\Controllers\Admin; 
 
use App\Http\Controllers\Controller; 
use App\Models\Appointment; 
use App\Models\Service; 
use App\Models\User; 
 
class DashboardController extends Controller 
{ 
    public function index() 
    { 
        $today = Appointment::whereDate('appointment_date', today())->count(); 
        $pending = Appointment::where('status', 'pending')->count(); 
        $clients = User::where('role', 'client')->count(); 
        $services = Service::where('is_available', true)->count(); 
 
        $appointments = Appointment::with(['user', 'service']) 
            ->whereDate('appointment_date', '>=', today()) 
            ->whereNotIn('status', ['cancelled']) 
            ->orderBy('appointment_date') 
            ->orderBy('appointment_time') 
            ->take(10) 
            ->get(); 

        /*
         * Get the 5 most booked services.
         *
         * Cancelled appointments are excluded because
         * they should not count toward service bookings.
         */
        $popularServices = Appointment::with('service')
            ->select('service_id')
            ->selectRaw('COUNT(*) as total_bookings')
            ->whereNotIn('status', ['cancelled'])
            ->groupBy('service_id')
            ->orderByDesc('total_bookings')
            ->limit(5)
            ->get();
 
        return view('admin.dashboard', compact(
            'today',
            'pending',
            'clients',
            'services',
            'appointments',
            'popularServices'
        )); 
    } 
}