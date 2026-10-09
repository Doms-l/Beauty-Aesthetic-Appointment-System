<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Service;
use App\Models\User;
use Carbon\Carbon;

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
            ->counted()
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
            ->counted()
            ->groupBy('service_id')
            ->orderByDesc('total_bookings')
            ->limit(5)
            ->get();

        $incomeAnalytics = $this->incomeAnalytics();

        // names for the raffle wheel ("Load registered clients")
        $raffleClients = User::where('role', 'client')
            ->get()
            ->pluck('full_name')
            ->filter()
            ->values()
            ->all();

        return view('admin.dashboard', compact(
            'today',
            'pending',
            'clients',
            'services',
            'appointments',
            'popularServices',
            'incomeAnalytics',
            'raffleClients'
        ));
    }


    /*
     * INCOME ANALYTICS
     *
     * Income = the price of the service of every appointment that
     * is NOT cancelled and NOT archived. Those are never counted
     * anywhere (income, bookings, or service breakdown).
     *
     * "Earned"   = completed appointments
     * "Expected" = pending / confirmed / rescheduled appointments
     */
    private function incomeAnalytics(): array
    {
        $now = Carbon::now();

        // Every period we show: how many buckets, how to label / key them
        $definitions = [
            'weekly' => [
                'count' => 8,
                'start' => fn ($i) => $now->copy()->startOfWeek()->subWeeks($i),
                'end'   => fn (Carbon $s) => $s->copy()->endOfWeek(),
                'label' => fn (Carbon $s) => $s->format('M d'),
                'title' => 'Last 8 weeks',
            ],
            'monthly' => [
                'count' => 12,
                'start' => fn ($i) => $now->copy()->startOfMonth()->subMonths($i),
                'end'   => fn (Carbon $s) => $s->copy()->endOfMonth(),
                'label' => fn (Carbon $s) => $s->format('M Y'),
                'title' => 'Last 12 months',
            ],
            'quarterly' => [
                'count' => 6,
                'start' => fn ($i) => $now->copy()->startOfQuarter()->subQuarters($i),
                'end'   => fn (Carbon $s) => $s->copy()->endOfQuarter(),
                'label' => fn (Carbon $s) => 'Q' . $s->quarter . ' ' . $s->year,
                'title' => 'Last 6 quarters',
            ],
            'annual' => [
                'count' => 5,
                'start' => fn ($i) => $now->copy()->startOfYear()->subYears($i),
                'end'   => fn (Carbon $s) => $s->copy()->endOfYear(),
                'label' => fn (Carbon $s) => (string) $s->year,
                'title' => 'Last 5 years',
            ],
        ];

        // One query: every non-cancelled appointment with its service
        $rows = Appointment::with('service:id,name,price')
            ->counted()
            ->get(['id', 'service_id', 'appointment_date', 'status']);

        $periods = [];

        foreach ($definitions as $key => $def) {

            $buckets = [];

            // oldest first, so the chart reads left → right
            for ($i = $def['count'] - 1; $i >= 0; $i--) {

                $start = $def['start']($i)->startOfDay();
                $end   = $def['end']($start);

                $income = 0.0;
                $earned = 0.0;
                $count  = 0;

                foreach ($rows as $row) {

                    $date = $row->appointment_date;

                    if ($date->lt($start) || $date->gt($end)) {
                        continue;
                    }

                    $price = (float) ($row->service->price ?? 0);

                    $income += $price;
                    $count++;

                    if ($row->status === 'completed') {
                        $earned += $price;
                    }
                }

                $buckets[] = [
                    'label'    => $def['label']($start),
                    'income'   => round($income, 2),
                    'earned'   => round($earned, 2),
                    'expected' => round($income - $earned, 2),
                    'bookings' => $count,
                    'current'  => $i === 0,
                ];
            }

            // Income per service across the whole range shown
            $rangeStart = $def['start']($def['count'] - 1)->startOfDay();
            $rangeEnd   = $def['end']($def['start'](0)->startOfDay());

            $byService = [];

            foreach ($rows as $row) {

                $date = $row->appointment_date;

                if ($date->lt($rangeStart) || $date->gt($rangeEnd)) {
                    continue;
                }

                $name = $row->service->name ?? 'Unknown service';

                $byService[$name] ??= ['name' => $name, 'bookings' => 0, 'income' => 0.0];
                $byService[$name]['bookings']++;
                $byService[$name]['income'] += (float) ($row->service->price ?? 0);
            }

            $byService = collect($byService)
                ->sortByDesc('income')
                ->values()
                ->all();

            $periods[$key] = [
                'title'    => $def['title'],
                'buckets'  => $buckets,
                'services' => $byService,
                'total'    => round(array_sum(array_column($buckets, 'income')), 2),
                'earned'   => round(array_sum(array_column($buckets, 'earned')), 2),
                'bookings' => array_sum(array_column($buckets, 'bookings')),
            ];
        }

        // Quick summary cards (current week / month / quarter / year)
        $summary = [
            'week'    => end($periods['weekly']['buckets'])['income'],
            'month'   => end($periods['monthly']['buckets'])['income'],
            'quarter' => end($periods['quarterly']['buckets'])['income'],
            'year'    => end($periods['annual']['buckets'])['income'],
            'all'     => round($rows->sum(fn ($r) => (float) ($r->service->price ?? 0)), 2),
            'earned'  => round($rows->where('status', 'completed')
                            ->sum(fn ($r) => (float) ($r->service->price ?? 0)), 2),
        ];

        $summary['expected'] = round($summary['all'] - $summary['earned'], 2);

        return [
            'periods'      => $periods,
            'summary'      => $summary,
            'generated_at' => $now->format('F d, Y h:i A'),
        ];
    }
}
