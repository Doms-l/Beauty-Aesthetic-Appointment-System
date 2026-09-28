@extends('layouts.app')

@section('title', 'Manage Appointments | M. Cares')

@section('content')
<section class="page-hero"><div class="container"><span class="eyebrow">ADMIN · APPOINTMENTS</span><h1>Manage appointment requests</h1><p>Confirm, reschedule, complete, or cancel client requests.</p></div></section>
<section class="section compact"><div class="container"><div class="table-wrap"><table><thead><tr><th>Date</th><th>Client</th><th>Service</th><th>Status</th><th>Staff</th><th>Save</th></tr></thead><tbody>
@forelse($appointments as $appointment)
<tr><td>{{ $appointment->appointment_date->format('M d, Y') }}<br>{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</td><td>{{ $appointment->user->full_name }}<br><small>{{ $appointment->user->phone }}</small></td><td>{{ $appointment->service->name }}</td><td><form method="POST" action="{{ auth()->user()->isAdmin() ? route('admin.appointments.update', $appointment) : route('staff.appointments.update', $appointment) }}" class="table-form">@csrf @method('PATCH')<select name="status"><option value="pending" @selected($appointment->status==='pending')>Pending</option><option value="confirmed" @selected($appointment->status==='confirmed')>Confirmed</option><option value="completed" @selected($appointment->status==='completed')>Completed</option><option value="rescheduled" @selected($appointment->status==='rescheduled')>Rescheduled</option><option value="cancelled" @selected($appointment->status==='cancelled')>Cancelled</option></select></td><td><select name="staff_id"><option value="">Unassigned</option>@foreach($staff as $member)<option value="{{ $member->id }}" @selected($appointment->staff_id===$member->id)>{{ $member->user->full_name }}</option>@endforeach</select></td><td><button class="small-button">Save</button></form></td></tr>
@empty<tr><td colspan="6">No appointments found.</td></tr>@endforelse
</tbody></table></div><div class="pagination">{{ $appointments->links() }}</div></div></section>
@endsection
