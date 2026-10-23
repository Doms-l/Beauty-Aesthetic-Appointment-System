@extends('layouts.app')

@section('title', 'Staff Dashboard | M. Cares')

@section('content')

@include('partials.dashboard-slider', [
    'eyebrow' => 'STAFF',
    'title'   => 'Welcome, ' . auth()->user()->first_name,
    'text'    => 'Your appointments for today and the days ahead, all in one place.',
    'admin'   => true,
])

<section class="section compact">
    <div class="container">

        @if($errors->any())
            <p style="color:#d64b6b;font-weight:600;">{{ $errors->first() }}</p>
        @endif

        @unless($staff)
            <div class="panel" style="margin-bottom:20px;">
                <strong>Your account has no staff profile yet.</strong>
                <p class="muted" style="margin:6px 0 0;">Ask the admin to finish setting up your staff account. Until then you will not see any appointments.</p>
            </div>
        @endunless

        <div class="stat-grid">

            <div class="stat-card">
                <span>Today's appointments</span>
                <strong>{{ $stats['today'] }}</strong>
            </div>

            <div class="stat-card">
                <span>Upcoming</span>
                <strong>{{ $stats['upcoming'] }}</strong>
            </div>

            <div class="stat-card">
                <span>To confirm</span>
                <strong>{{ $stats['pending'] }}</strong>
            </div>

            <div class="stat-card">
                <span>Completed this month</span>
                <strong>{{ $stats['completed'] }}</strong>
            </div>

        </div>

        {{-- TODAY --}}
        <div class="panel" style="margin-top:22px;">
            <div class="section-heading">
                <div>
                    <span class="eyebrow">TODAY</span>
                    <h2>Today's schedule · {{ now()->format('l, M d') }}</h2>
                </div>
            </div>
            @include('staff.partials.appointment-table', ['rows' => $todayList, 'staff' => $staff, 'empty' => 'No appointments for you today.'])
        </div>

        {{-- NEED CONFIRMING --}}
        <div class="panel" style="margin-top:22px;">
            <div class="section-heading">
                <div>
                    <span class="eyebrow">ACTION NEEDED</span>
                    <h2>Waiting for your confirmation</h2>
                </div>
            </div>
            @include('staff.partials.appointment-table', ['rows' => $pendingList, 'staff' => $staff, 'empty' => 'Nothing is waiting for confirmation.'])
        </div>

        {{-- UPCOMING --}}
        <div class="panel" style="margin-top:22px;">
            <div class="section-heading inline-heading">
                <div>
                    <span class="eyebrow">UPCOMING</span>
                    <h2>Next appointments</h2>
                </div>
                <a href="{{ route('staff.appointments') }}">All my appointments →</a>
            </div>
            @include('staff.partials.appointment-table', ['rows' => $upcomingList, 'staff' => $staff, 'empty' => 'No upcoming appointments yet.'])
        </div>

        {{-- UNASSIGNED --}}
        <div class="panel" style="margin-top:22px;">
            <div class="section-heading">
                <div>
                    <span class="eyebrow">OPEN REQUESTS</span>
                    <h2>Not assigned yet</h2>
                    <p class="muted" style="margin:4px 0 0;">Clients who chose "No preference". Take one to manage it.</p>
                </div>
            </div>
            @include('staff.partials.appointment-table', ['rows' => $claimList, 'staff' => $staff, 'empty' => 'No open requests right now.'])
        </div>

    </div>
</section>

@include('staff.partials.appointment-modal')

@endsection
