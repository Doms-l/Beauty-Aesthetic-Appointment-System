@php
    $tier = \App\Support\ClientTier::for(auth()->user());
@endphp

<div class="panel" style="margin-bottom:20px;">
    <span class="eyebrow">YOUR LEVEL</span>

    <h2 style="margin:6px 0;">
        {{ $tier->key === 'vip' ? '👑 ' : '' }}{{ $tier->name() }}
    </h2>

    <p style="margin:0 0 8px;">
        {{ $tier->completed }} completed {{ $tier->completed === 1 ? 'visit' : 'visits' }}.
        @if ($tier->visitsToNext() !== null)
            {{ $tier->visitsToNext() }} more to become a {{ $tier->nextName() }}.
        @else
            You are at the highest level.
        @endif
    </p>

    <p class="muted" style="margin:0;">
        Booking limits: up to {{ $tier->limit('max_active') }}
        upcoming {{ $tier->limit('max_active') === 1 ? 'appointment' : 'appointments' }} at a time,
        {{ $tier->limit('max_per_day') }} requests per day,
        and bookings up to {{ $tier->limit('advance_days') }} days ahead.
    </p>

    @if (auth()->user()->cancel_strikes > 0)
        <p style="margin:10px 0 0;font-weight:600;">
            ⚠ You have cancelled {{ auth()->user()->cancel_strikes }}
            of {{ config('loyalty.cancel_ban_limit', 3) }} appointments.
            At {{ config('loyalty.cancel_ban_limit', 3) }} cancellations your account is banned.
        </p>
    @endif
</div>
