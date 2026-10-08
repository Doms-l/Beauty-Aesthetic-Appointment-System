@extends('layouts.app')

@section('title', 'Client Dashboard | M. Cares')

@section('content')
<section class="dashboard-header">
    <div class="container"><span class="eyebrow">CLIENT DASHBOARD</span><h1>Welcome, {{ auth()->user()->first_name }}.</h1><p>Manage your profile and beauty appointments here.</p></div>
</section>
<section class="section compact">
    <div class="container">
        <div class="dashboard-actions"><a class="primary-button" href="{{ route('client.appointments.create') }}">+ Book appointment</a><a class="secondary-button" href="{{ route('client.profile') }}">Edit profile</a></div>
        @include('partials.promo-banner', ['compact' => true])

        <div class="dashboard-grid">
            <div class="panel spotlight">
                <span class="eyebrow">NEXT APPOINTMENT</span>
                @if($upcoming)
                    <h2>{{ $upcoming->service->name }}</h2>
                    <p>{{ $upcoming->appointment_date->format('F d, Y') }} · {{ \Carbon\Carbon::parse($upcoming->appointment_time)->format('h:i A') }}</p>
                    <span class="status {{ $upcoming->status }}">{{ ucfirst($upcoming->status) }}</span>
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
