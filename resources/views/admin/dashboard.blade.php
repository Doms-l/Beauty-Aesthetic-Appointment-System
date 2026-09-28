@extends('layouts.app')

@section('title', 'Admin Dashboard | M. Cares')

@section('content')
<section class="dashboard-header"><div class="container"><span class="eyebrow">ADMINISTRATION</span><h1>Clinic overview</h1><p>Monitor appointments, clients, services, and daily clinic activity.</p></div></section>
<section class="section compact"><div class="container">
<div class="stat-grid"><div class="stat-card"><span>Today's appointments</span><strong>{{ $today }}</strong></div><div class="stat-card"><span>Pending requests</span><strong>{{ $pending }}</strong></div><div class="stat-card"><span>Registered clients</span><strong>{{ $clients }}</strong></div><div class="stat-card"><span>Available services</span><strong>{{ $services }}</strong></div></div>
<div class="admin-links"><a href="{{ route('admin.appointments') }}">Appointments →</a><a href="{{ route('admin.services') }}">Services →</a><a href="{{ route('admin.staff') }}">Staff →</a></div>
<div class="panel"><div class="section-heading inline-heading"><div><span class="eyebrow">UPCOMING</span><h2>Appointment schedule</h2></div><a href="{{ route('admin.appointments') }}">Manage →</a></div><div class="table-wrap"><table><thead><tr><th>Date</th><th>Client</th><th>Service</th><th>Status</th></tr></thead><tbody>@forelse($appointments as $appointment)<tr><td>{{ $appointment->appointment_date->format('M d, Y') }} {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</td><td>{{ $appointment->user->full_name }}</td><td>{{ $appointment->service->name }}</td><td><span class="status {{ $appointment->status }}">{{ ucfirst($appointment->status) }}</span></td></tr>@empty<tr><td colspan="4">No upcoming appointments.</td></tr>@endforelse</tbody></table></div></div>
</div></section>
@endsection
