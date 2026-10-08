@extends('layouts.app') 
 
@section('title', 'Admin Dashboard | M. Cares') 
 
@section('content') 

<section class="dashboard-header">
    <div class="container">
        <span class="eyebrow">ADMINISTRATION</span>

        <h1>Clinic overview</h1>

        <p>
            Monitor appointments, clients, services, and daily clinic activity.
        </p>
    </div>
</section>


<section class="section compact">

    <div class="container">

        {{-- Dashboard Statistics --}}
        <div class="stat-grid">

            <div class="stat-card">
                <span>Today's appointments</span>
                <strong>{{ $today }}</strong>
            </div>

            <div class="stat-card">
                <span>Pending requests</span>
                <strong>{{ $pending }}</strong>
            </div>

            <div class="stat-card">
                <span>Registered clients</span>
                <strong>{{ $clients }}</strong>
            </div>

            <div class="stat-card">
                <span>Available services</span>
                <strong>{{ $services }}</strong>
            </div>

        </div>


        {{-- Active promotion --}}
        @include('partials.promo-banner', ['compact' => true, 'admin' => true])

        {{-- Admin Quick Links --}}
        <div class="admin-links">

            <a href="{{ route('admin.appointments') }}">
                Appointments →
            </a>

            <a href="{{ route('admin.services') }}">
                Services →
            </a>

            <a href="{{ route('admin.staff') }}">
                Staff →
            </a>

        </div>


        {{-- =====================================================
             MOST BOOKED SERVICES
             ===================================================== --}}

        <div class="panel popular-services-panel">

            <div class="section-heading">

                <div>

                    <span class="eyebrow">
                        ANALYTICS
                    </span>

                    <h2>
                        Most Booked Services
                    </h2>

                    <p>
                        Services with the highest number of
                        non-cancelled appointment requests.
                    </p>

                </div>

            </div>


            @if($popularServices->count())

                @php
                    $maxBookings = $popularServices->max('total_bookings');
                @endphp


                <div class="popular-services-chart">

                    @foreach($popularServices as $popularService)

                        @php
                            $percentage = $maxBookings > 0
                                ? ($popularService->total_bookings / $maxBookings) * 100
                                : 0;
                        @endphp


                        <div class="chart-row">

                            <div class="chart-label">

                                <span>
                                    {{ $popularService->service->name }}
                                </span>

                                <strong>
                                    {{ $popularService->total_bookings }}
                                    {{ $popularService->total_bookings == 1 ? 'booking' : 'bookings' }}
                                </strong>

                            </div>


                            <div class="chart-bar-background">

                                <div
                                    class="chart-bar"
                                    style="width: {{ $percentage }}%;"
                                ></div>

                            </div>

                        </div>

                    @endforeach

                </div>

            @else

                <div class="chart-empty">

                    <p>
                        No appointment data available yet.
                    </p>

                </div>

            @endif

        </div>


        {{-- =====================================================
             UPCOMING APPOINTMENTS
             ===================================================== --}}

        <div class="panel">

            <div class="section-heading inline-heading">

                <div>

                    <span class="eyebrow">
                        UPCOMING
                    </span>

                    <h2>
                        Appointment schedule
                    </h2>

                </div>

                <a href="{{ route('admin.appointments') }}">
                    Manage →
                </a>

            </div>


            <div class="table-wrap">

                <table>

                    <thead>

                        <tr>
                            <th>Date</th>
                            <th>Client</th>
                            <th>Service</th>
                            <th>Status</th>
                        </tr>

                    </thead>


                    <tbody>

                        @forelse($appointments as $appointment)

                            <tr>

                                <td>
                                    {{ $appointment->appointment_date->format('M d, Y') }}

                                    {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}
                                </td>

                                <td>
                                    {{ $appointment->user->full_name }}
                                </td>

                                <td>
                                    {{ $appointment->service->name }}
                                </td>

                                <td>

                                    <span class="status {{ $appointment->status }}">
                                        {{ ucfirst($appointment->status) }}
                                    </span>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="4">
                                    No upcoming appointments.
                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</section> 

@endsection