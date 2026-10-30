{{-- Package progress for the CLIENT: "Session 2 of 4", dots, finished dates and the next visit.
     $appointment : Appointment of a package service --}}
@php
    $cpTotal = (int) $appointment->service->sessions;
    $cpDone  = $appointment->sessions;
    $cpNext  = $appointment->next_visit_date;
    $cpOpen  = $appointment->canLogSession();
    $cpNo    = $cpDone->count() + 1;
@endphp

<div class="cpkg">
    <div class="cpkg-head">
        <strong>Session {{ min($cpDone->count() + ($cpOpen ? 1 : 0), $cpTotal) }} of {{ $cpTotal }}</strong>
        <span class="cpkg-dots" aria-hidden="true">
            @for($i = 1; $i <= $cpTotal; $i++)
                <i class="{{ $i <= $cpDone->count() ? 'on' : '' }}"></i>
            @endfor
        </span>
    </div>

    <div class="cpkg-line">{{ $cpDone->count() }} of {{ $cpTotal }} done</div>

    @if($cpOpen)
        <div class="cpkg-next">
            Next visit (session {{ $cpNo }}):
            @if($cpNext)
                <b>{{ $cpNext->format('M d, Y') }}@if($cpDone->isEmpty()), {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}@endif</b>
            @else
                <b>Not scheduled yet</b> <span class="cpkg-muted">— the clinic will set the date</span>
            @endif
        </div>
    @else
        <div class="cpkg-next">
            @if($appointment->status === 'cancelled') Package cancelled. @else All sessions finished. @endif
        </div>
    @endif

    @foreach($cpDone as $session)
        <div class="cpkg-line cpkg-muted">✓ Session {{ $session->session_no }} · {{ $session->session_date->format('M d, Y') }}</div>
    @endforeach
</div>

@once
<style>
    .cpkg { margin-top: 8px; padding: 8px 10px; border-radius: 12px; border: 1px solid #E8AECF; background: rgba(243, 188, 210, .18); font-size: 12px; min-width: 190px; color: var(--text); }
    .cpkg-head { display: flex; align-items: center; justify-content: space-between; gap: 8px; }
    .cpkg-dots { display: inline-flex; gap: 4px; }
    .cpkg-dots i { width: 10px; height: 10px; border-radius: 50%; border: 1.5px solid #D6B36A; background: transparent; }
    .cpkg-dots i.on { background: #2e9a5d; border-color: #2e9a5d; }
    .cpkg-line { margin-top: 4px; }
    .cpkg-next { margin-top: 6px; padding-top: 6px; border-top: 1px dotted #E8AECF; }
    .cpkg-muted { color: var(--text-muted); }
</style>
@endonce
