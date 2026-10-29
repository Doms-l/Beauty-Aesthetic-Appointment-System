@extends('layouts.app')

@section('title', 'My Appointments | M. Care')

@section('content')

<section class="page-hero">
    <div class="container">
        <span class="eyebrow">STAFF · APPOINTMENTS</span>
        <h1>My appointments</h1>
        <p>View your schedule, confirm requests, update dates and mark appointments as completed.</p>
    </div>
</section>

<section class="section compact">
    <div class="container">

        @if($errors->any())
            <p style="color:#d64b6b;font-weight:600;">{{ $errors->first() }}</p>
        @endif

        <div class="dashboard-actions" style="margin-bottom:12px;">
            @foreach(['today' => 'Today', 'upcoming' => 'Upcoming', 'past' => 'Past', 'all' => 'All'] as $key => $label)
                <a class="{{ $view === $key ? 'primary-button' : 'secondary-button' }}"
                   href="{{ route('staff.appointments', array_filter(['view' => $key, 'status' => $status])) }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        <form method="GET" action="{{ route('staff.appointments') }}" class="form-stack" style="margin:0 0 16px; max-width:260px;">
            <input type="hidden" name="view" value="{{ $view }}">
            <select name="status" onchange="this.form.submit()">
                <option value="">All statuses</option>
                @foreach(['pending','confirmed','rescheduled','completed','cancelled'] as $s)
                    <option value="{{ $s }}" @selected($status === $s)>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
        </form>

        @include('staff.partials.appointment-table', ['rows' => $appointments, 'staff' => $staff, 'empty' => 'No appointments found.'])

        <div class="pagination">{{ $appointments->links() }}</div>

    </div>
</section>

@include('staff.partials.appointment-modal')

@endsection
