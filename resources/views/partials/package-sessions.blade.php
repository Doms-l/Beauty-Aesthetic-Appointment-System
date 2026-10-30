{{-- Progress of a package booking + "Complete session" form.
     Used in the admin and staff appointment tables.
     $appointment : Appointment of a package service (sessions relation loaded or lazy) --}}
@php
    $pkgUser    = auth()->user();
    $pkgIsAdmin = $pkgUser->isAdmin();
    $pkgPrefix  = $pkgIsAdmin ? 'admin' : 'staff';
    $pkgTotal   = (int) $appointment->service->sessions;
    $pkgDone    = $appointment->sessions;

    // staff can only act on their own booking, admin on any
    $pkgCanAct  = $pkgIsAdmin
        || \App\Support\StaffAppointments::canEdit($appointment, $pkgUser->staffProfile);
@endphp

<div class="pkg-box">

    <div class="pkg-head">
        <strong>Sessions {{ $pkgDone->count() }} of {{ $pkgTotal }}</strong>
        <span class="pkg-dots" aria-hidden="true">
            @for($i = 1; $i <= $pkgTotal; $i++)
                <i class="{{ $i <= $pkgDone->count() ? 'on' : '' }}"></i>
            @endfor
        </span>
    </div>

    @foreach($pkgDone as $session)
        <div class="pkg-row">
            <span>#{{ $session->session_no }} · {{ $session->session_date->format('M d, Y') }}</span>
            <a href="{{ route($pkgPrefix . '.appointments.sessions.receipt', $session) }}"
               target="_blank" rel="noopener">🧾 Receipt</a>
        </div>
    @endforeach

    @if($appointment->canLogSession())

        @php
            $nextNo   = $pkgDone->count() + 1;
            $planned  = $appointment->next_session_date;
            $lastDate = $pkgDone->last()?->session_date;
            // the visit is recorded on the planned day once it has come, otherwise today
            $doneDate = ($planned && $planned->lte(today())) ? $planned : today();
        @endphp

        <div class="pkg-next">
            Next visit (session {{ $nextNo }}):
            <b>{{ $planned ? $planned->format('M d, Y') : 'not set' }}</b>
        </div>

        @if($pkgCanAct)

            <form method="POST"
                  action="{{ route($pkgPrefix . '.appointments.sessions.schedule', $appointment) }}"
                  class="pkg-form">
                @csrf
                @method('PATCH')
                <input type="date" name="next_session_date"
                       value="{{ $planned?->format('Y-m-d') }}"
                       min="{{ max(today(), $lastDate ?? today())->format('Y-m-d') }}"
                       aria-label="Planned day of session {{ $nextNo }}">
                <button class="pkg-set">📅 Set next visit</button>
            </form>

            <form method="POST"
                  action="{{ route($pkgPrefix . '.appointments.sessions.store', $appointment) }}"
                  class="pkg-form">
                @csrf
                <input type="hidden" name="session_date" value="{{ $doneDate->format('Y-m-d') }}">
                <button class="pkg-complete"
                        onclick="return confirm('Complete session {{ $nextNo }} of {{ $pkgTotal }} for {{ e($appointment->user->full_name) }} on {{ $doneDate->format('M d, Y') }}?')">
                    ✓ Complete session {{ $nextNo }}
                </button>
            </form>

        @endif

    @endif

    @if($pkgIsAdmin && $pkgDone->isNotEmpty())
        <form method="POST"
              action="{{ route('admin.appointments.sessions.undo', $appointment) }}"
              class="pkg-undo">
            @csrf
            @method('DELETE')
            <button onclick="return confirm('Undo session {{ $pkgDone->count() }}? Use this only if it was completed by mistake.')">
                ↩ Undo session {{ $pkgDone->count() }}
            </button>
        </form>
    @endif

</div>

@once
<style>
    .pkg-box {
        margin-top: 8px;
        padding: 8px 10px;
        border-radius: 12px;
        border: 1px solid #E8AECF;
        background: rgba(243, 188, 210, .18);
        font-size: 12px;
        min-width: 190px;
    }
    .pkg-head { display: flex; align-items: center; justify-content: space-between; gap: 8px; }
    .pkg-dots { display: inline-flex; gap: 4px; }
    .pkg-dots i {
        width: 10px; height: 10px; border-radius: 50%;
        border: 1.5px solid #D6B36A; background: transparent;
    }
    .pkg-dots i.on { background: #2e9a5d; border-color: #2e9a5d; }
    .pkg-row {
        display: flex; align-items: center; justify-content: space-between; gap: 8px;
        margin-top: 5px; padding-top: 5px; border-top: 1px dotted #E8AECF;
    }
    .pkg-row a { font-weight: 700; text-decoration: none; white-space: nowrap; color: inherit; }
    .pkg-row a:hover { text-decoration: underline; }
    .pkg-form { display: grid; gap: 6px; margin-top: 8px; }
    .pkg-form input[type="date"] { font: inherit; font-size: 12px; padding: 5px 8px; border-radius: 8px; border: 1px solid #E8AECF; width: 100%; box-sizing: border-box; }
    .pkg-complete {
        border: 0; cursor: pointer; font: inherit; font-weight: 700; font-size: 12px;
        padding: 7px 10px; border-radius: 999px; color: #fff; background: #2e9a5d;
    }
    .pkg-complete:hover { filter: brightness(1.08); }
    .pkg-next { margin-top: 8px; padding-top: 6px; border-top: 1px dotted #E8AECF; }
    .pkg-set {
        border: 1px solid #D6B36A; cursor: pointer; font: inherit; font-weight: 700; font-size: 12px;
        padding: 6px 10px; border-radius: 999px; color: #62444D; background: #fff;
    }
    .pkg-undo { margin-top: 6px; }
    .pkg-undo button {
        border: 0; background: none; cursor: pointer; font: inherit; font-size: 11px;
        color: #d64b6b; text-decoration: underline; padding: 0;
    }
</style>
@endonce
