@extends('layouts.app')

@section('title', 'Client Dashboard | M. Care')

@section('content')
@include('partials.dashboard-slider', [
    'eyebrow' => 'CLIENT DASHBOARD',
    'title'   => 'Welcome, ' . auth()->user()->first_name . '.',
    'text'    => 'Manage your profile and beauty appointments here.',
])
<section class="section compact">
    <div class="container">
        <div class="dashboard-actions"><a class="primary-button" href="{{ route('client.appointments.create') }}">+ Book appointment</a><a class="secondary-button" href="{{ route('client.profile') }}">Edit profile</a></div>
        @include('partials.client-tier')
        <div class="dashboard-grid">
            <div class="panel spotlight">
                <span class="eyebrow">NEXT APPOINTMENT</span>
                @if($upcoming)
                    <h2>{{ $upcoming->service->name }}</h2>
                    @if($upcoming->is_package)
                        {{-- later sessions only have a planned day, the first one also has the booked time --}}
                        <p>{{ $upcoming->next_visit_date->format('F d, Y') }}@if($upcoming->sessions_done === 0) · {{ \Carbon\Carbon::parse($upcoming->appointment_time)->format('h:i A') }}@endif</p>
                        @include('partials.client-package-progress', ['appointment' => $upcoming])
                    @else
                        <p>{{ $upcoming->appointment_date->format('F d, Y') }} · {{ \Carbon\Carbon::parse($upcoming->appointment_time)->format('h:i A') }}</p>
                    @endif
                    <span class="status {{ $upcoming->status }}">{{ ucfirst($upcoming->status) }}</span>
                @elseif($waitingPackage)
                    <h2>{{ $waitingPackage->service->name }}</h2>
                    <p>Your next session date is not set yet.</p>
                    @include('partials.client-package-progress', ['appointment' => $waitingPackage])
                    <span class="status {{ $waitingPackage->status }}">{{ ucfirst($waitingPackage->status) }}</span>
                @else
                    <h2>No upcoming appointment</h2>
                    <p>Book your next beauty service when you're ready.</p>
                @endif
            </div>
            <div class="panel"><span class="eyebrow">ACCOUNT</span><h2>{{ auth()->user()->full_name }}</h2><p>{{ auth()->user()->email }}</p><p>{{ auth()->user()->phone }}</p><a href="{{ route('client.profile') }}">View profile →</a></div>
        </div>
    </div>
</section>
<section class="section soft-section compact">
    <div class="container"><div class="section-heading inline-heading"><div><span class="eyebrow">APPOINTMENT HISTORY</span><h2>Your appointments</h2></div><a href="{{ route('client.appointments') }}">View all →</a></div>
        <div class="table-wrap"><table><thead><tr><th>Date</th><th>Service</th><th>Status</th></tr></thead><tbody>
        @forelse($appointments as $appointment)<tr><td>{{ $appointment->appointment_date->format('M d, Y') }}</td><td>{{ $appointment->service->name }}</td><td><span class="status {{ $appointment->status }}">{{ ucfirst($appointment->status) }}</span></td></tr>@empty<tr><td colspan="3">No appointment records yet.</td></tr>@endforelse
        </tbody></table></div>
    </div>
</section>
@endsection
