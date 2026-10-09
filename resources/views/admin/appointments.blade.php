@extends('layouts.app')

@section('title', 'Manage Appointments | M. Cares')

@section('content')

<section class="page-hero">
    <div class="container">
        <span class="eyebrow">ADMIN · APPOINTMENTS</span>
        <h1>{{ $showArchived ? 'Archived appointments' : 'Manage appointment requests' }}</h1>
        <p>
            @if($showArchived)
                Archived appointments are hidden from the list and from all analytics.
            @else
                Confirm, reschedule, complete, or cancel client requests. Archive cancelled ones to remove them from the analytics.
            @endif
        </p>
    </div>
</section>

<section class="section compact">

    <div class="container">

        {{-- Active / Archive switch (admin only) --}}
        @if(auth()->user()->isAdmin())
            <div class="dashboard-actions" style="margin-bottom:16px;">
                @if($showArchived)
                    <a class="primary-button" href="{{ route('admin.appointments') }}">← Back to appointments</a>
                @else
                    <a class="primary-button" href="{{ route('admin.appointments', ['archived' => 1]) }}">
                        🗄 View archive ({{ $archivedCount }})
                    </a>
                @endif
            </div>
        @endif

        @if($errors->any())
            <p style="color:#d64b6b;">{{ $errors->first() }}</p>
        @endif

        <div class="table-wrap">

            <table>

                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Client</th>
                        <th>Service</th>
                        <th>Status</th>
                        <th>Staff</th>
                        <th>Save</th>
                        @if(auth()->user()->isAdmin())
                            <th>Archive</th>
                        @endif
                    </tr>
                </thead>

                <tbody>

                @forelse($appointments as $appointment)

                    <tr>

                        <td>
                            {{ $appointment->appointment_date->format('M d, Y') }}<br>
                            {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}
                        </td>

                        <td>
                            {{ $appointment->user->full_name }}<br>
                            <small>{{ $appointment->user->phone }}</small>
                        </td>

                        <td>{{ $appointment->service->name }}</td>

                        <td>
                            <form method="POST"
                                  action="{{ auth()->user()->isAdmin() ? route('admin.appointments.update', $appointment) : route('staff.appointments.update', $appointment) }}"
                                  class="table-form">
                                @csrf
                                @method('PATCH')

                                <select name="status">
                                    <option value="pending" @selected($appointment->status==='pending')>Pending</option>
                                    <option value="confirmed" @selected($appointment->status==='confirmed')>Confirmed</option>
                                    <option value="completed" @selected($appointment->status==='completed')>Completed</option>
                                    <option value="rescheduled" @selected($appointment->status==='rescheduled')>Rescheduled</option>
                                    <option value="cancelled" @selected($appointment->status==='cancelled')>Cancelled</option>
                                </select>
                        </td>

                        <td>
                                <select name="staff_id">
                                    <option value="">{{ $appointment->with_owner ? 'Owner (requested)' : 'Unassigned' }}</option>
                                    @foreach($staff as $member)
                                        <option value="{{ $member->id }}" @selected($appointment->staff_id===$member->id)>
                                            {{ $member->user->full_name }}
                                        </option>
                                    @endforeach
                                </select>
                        </td>

                        <td>
                                <button class="small-button">Save</button>
                            </form>
                        </td>

                        @if(auth()->user()->isAdmin())
                            <td>
                                @if($appointment->archived_at)

                                    <form method="POST" action="{{ route('admin.appointments.restore', $appointment) }}" class="inline-form">
                                        @csrf
                                        @method('PATCH')
                                        <button class="small-button">Restore</button>
                                    </form>

                                @elseif($appointment->status === 'cancelled')

                                    <form method="POST" action="{{ route('admin.appointments.archive', $appointment) }}" class="inline-form">
                                        @csrf
                                        @method('PATCH')
                                        <button class="small-button"
                                                onclick="return confirm('Archive this cancelled appointment? It will be removed from the analytics.')">
                                            🗄 Archive
                                        </button>
                                    </form>

                                @else
                                    —
                                @endif
                            </td>
                        @endif

                    </tr>

                @empty

                    <tr>
                        <td colspan="{{ auth()->user()->isAdmin() ? 7 : 6 }}">
                            {{ $showArchived ? 'No archived appointments.' : 'No appointments found.' }}
                        </td>
                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

        <div class="pagination">{{ $appointments->links() }}</div>

    </div>

</section>

@endsection
