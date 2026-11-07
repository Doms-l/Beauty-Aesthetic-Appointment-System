<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Service;
use App\Models\User;
use App\Support\WalkInSupport;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Appointment::whereDate('appointment_date', today())->count();
        $pending = Appointment::where('status', 'pending')->count();
        $clients = User::where('role', 'client')
            ->when(WalkInSupport::ready(), fn ($q) => $q->where('is_walk_in', false))
            ->count();
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

        // services added to a visit count as bookings too
        $extraCounts = WalkInSupport::ready()
            ? \App\Models\AppointmentExtra::query()
                ->select('service_id')
                ->selectRaw('COUNT(*) as total_bookings')
                ->whereHas('appointment', fn ($q) => $q->counted())
                ->groupBy('service_id')
                ->get()
                ->keyBy('service_id')
            : collect();

        if ($extraCounts->isNotEmpty()) {
            $all = Appointment::with('service')
                ->select('service_id')
                ->selectRaw('COUNT(*) as total_bookings')
                ->counted()
                ->groupBy('service_id')
                ->get()
                ->keyBy('service_id');

            foreach ($extraCounts as $serviceId => $row) {
                if ($all->has($serviceId)) {
                    $all[$serviceId]->total_bookings += $row->total_bookings;
                } else {
                    $row->setRelation('service', Service::find($serviceId));
                    $all[$serviceId] = $row;
                }
            }

            $popularServices = $all->filter(fn ($r) => $r->service)
                ->sortByDesc('total_bookings')
                ->take(5)
                ->values();
        }

        $incomeAnalytics = $this->incomeAnalytics();

        // names for the raffle wheel ("Load registered clients")
        $raffleClients = User::where('role', 'client')
            ->when(WalkInSupport::ready(), fn ($q) => $q->where('is_walk_in', false))
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


    /**
     * The money entries behind the analytics.
     *
     * Normal service: one entry (price), unless cancelled.
     * Package: one entry per finished session (earned, dated the day it was
     * done, kept even if the booking is cancelled later) plus one entry per
     * session still to come (expected, only while the booking is not cancelled).
     * Archived bookings are never counted.
     *
     * @return \Illuminate\Support\Collection<int, object{appointment_date: Carbon, status: string, name: string, amount: float}>
     */
    private function incomeEntries()
    {
        $appointments = Appointment::with([
                'service:id,name,price,sessions',
                'sessions:id,appointment_id,session_date',
                ...(WalkInSupport::ready() ? ['extras:id,appointment_id,service_id,price', 'extras.service:id,name'] : []),
            ])
            ->whereNull('archived_at')
            ->get(['id', 'service_id', 'appointment_date', 'next_session_date', 'status']);

        $entries = collect();

        foreach ($appointments as $appointment) {

            $service = $appointment->service;
            $name    = $service->name ?? 'Unknown service';

            if ($service && $service->is_package) {

                $share = (float) $service->per_session_price;

                foreach ($appointment->sessions as $session) {
                    $entries->push((object) [
                        'appointment_date' => $session->session_date,
                        'status'           => 'completed',
                        'name'             => $name,
                        'amount'           => $share,
                    ]);
                }

                if ($appointment->status !== 'cancelled') {
                    $left = max(0, (int) $service->sessions - $appointment->sessions->count());

                    for ($i = 0; $i < $left; $i++) {
                        $entries->push((object) [
                            'appointment_date' => ($i === 0 && $appointment->next_session_date)
                                ? $appointment->next_session_date
                                : $appointment->appointment_date,
                            'status'           => 'pending',
                            'name'             => $name,
                            'amount'           => $share,
                        ]);
                    }
                }

                continue;
            }

            if ($appointment->status === 'cancelled') {
                continue;
            }

            $entries->push((object) [
                'appointment_date' => $appointment->appointment_date,
                'status'           => $appointment->status,
                'name'             => $name,
                'amount'           => (float) ($service->price ?? 0),
            ]);

            // services the admin added to this same visit
            foreach ($appointment->extra_items as $extra) {
                $entries->push((object) [
                    'appointment_date' => $appointment->appointment_date,
                    'status'           => $appointment->status,
                    'name'             => $extra->service->name ?? 'Unknown service',
                    'amount'           => (float) $extra->price,
                ]);
            }
        }

        return $entries;
    }


    /*
     * INCOME ANALYTICS
     *
     * Income = the price of the service of every appointment that
     * is NOT cancelled and NOT archived. Those are never counted
     * anywhere (income, bookings, or service breakdown).
     *
     * A package (service with more than one session) counts its price
     * split evenly over its sessions: 3,999 for 4 sessions = 999.75 for
     * each session, so a finished package adds up to exactly 3,999.
     * Every session the staff completed is "Earned" on the day it happened;
     * the sessions still to come are "Expected" on the booking date.
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

        // One income entry per service (or per session of a package)
        $rows = $this->incomeEntries();

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

                    $price = (float) $row->amount;

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

                $name = $row->name;

                $byService[$name] ??= ['name' => $name, 'bookings' => 0, 'income' => 0.0];
                $byService[$name]['bookings']++;
                $byService[$name]['income'] += (float) $row->amount;
            }

            $byService = collect($byService)
                ->sortByDesc('income')
                ->values()
                ->all();

            // The current week / month / quarter / year only (used by the print option)
            $currentStart = $def['start'](0)->startOfDay();
            $currentEnd   = $def['end']($currentStart);

            $currentServices = [];
            $currentTotal    = 0.0;
            $currentCount    = 0;

            foreach ($rows as $row) {

                $date = $row->appointment_date;

                if ($date->lt($currentStart) || $date->gt($currentEnd)) {
                    continue;
                }

                $name  = $row->name;
                $price = (float) $row->amount;

                $currentServices[$name] ??= ['name' => $name, 'bookings' => 0, 'income' => 0.0];
                $currentServices[$name]['bookings']++;
                $currentServices[$name]['income'] += $price;

                $currentTotal += $price;
                $currentCount++;
            }

            $currentServices = collect($currentServices)
                ->sortByDesc('income')
                ->values()
                ->all();

            $periods[$key] = [
                'title'    => $def['title'],
                'buckets'  => $buckets,
                'services' => $byService,
                'current'  => [
                    'range'    => $currentStart->format('M d, Y') . ' - ' . $currentEnd->format('M d, Y'),
                    'services' => $currentServices,
                    'total'    => round($currentTotal, 2),
                    'bookings' => $currentCount,
                ],
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
            'all'     => round($rows->sum(fn ($r) => (float) $r->amount), 2),
            'earned'  => round($rows->where('status', 'completed')
                            ->sum(fn ($r) => (float) $r->amount), 2),
        ];

        $summary['expected'] = round($summary['all'] - $summary['earned'], 2);

        return [
            'periods'      => $periods,
            'summary'      => $summary,
            'generated_at' => $now->format('F d, Y h:i A'),
        ];
    }
}