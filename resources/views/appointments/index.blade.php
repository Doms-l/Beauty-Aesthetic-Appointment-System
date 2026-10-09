@extends('layouts.app')

@section('title', 'My Appointments | M. Cares')

@section('content')
<section class="page-hero"><div class="container"><span class="eyebrow">APPOINTMENTS</span><h1>My appointment requests</h1><p>Track your upcoming and past clinic appointments.</p></div></section>
<section class="section compact"><div class="container"><div class="dashboard-actions"><a class="primary-button" href="{{ route('client.appointments.create') }}">+ New appointment</a></div>
<div class="table-wrap"><table><thead><tr><th>Date</th><th>Time</th><th>Service</th><th>With</th><th>Status</th><th>Action</th></tr></thead><tbody>
@forelse($appointments as $appointment)
<tr><td>{{ $appointment->appointment_date->format('M d, Y') }}</td><td>{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</td><td>{{ $appointment->service->name }}</td><td>{{ $appointment->provider_label }}</td><td><span class="status {{ $appointment->status }}">{{ ucfirst($appointment->status) }}</span></td><td>@if(!in_array($appointment->status, ['cancelled','completed']))<form method="POST" action="{{ route('client.appointments.cancel', $appointment) }}" class="inline-form">@csrf @method('PATCH')<button class="danger-link" onclick="return confirm('Cancel this appointment?')">Cancel</button></form>@else — @endif</td></tr>
@empty<tr><td colspan="6">No appointment records yet.</td></tr>@endforelse
</tbody></table></div>
<div class="pagination">{{ $appointments->links() }}</div></div></section>
@endsection
