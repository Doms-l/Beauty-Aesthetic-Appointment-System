@extends('layouts.app')

@section('title', 'Manage Appointments | M. Care')

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
                @if(! $showArchived && ($walkInReady ?? false))
                    <button type="button" class="primary-button" id="open-walkin">➕ Add walk-in</button>
                @endif
                @if($showArchived || ($historyOnly ?? false))
                    <a class="primary-button" href="{{ route('admin.appointments') }}">← Back to appointments</a>
                @else
                    <a class="primary-button" href="{{ route('admin.appointments', ['view' => 'history']) }}">
                        ✅ Completed &amp; past ({{ $historyCount ?? 0 }})
                    </a>
                @endif
                @if(! $showArchived && ! ($historyOnly ?? false))
                    <a class="primary-button" href="{{ route('admin.appointments', ['archived' => 1]) }}">
                        🗄 View archive ({{ $archivedCount }})
                    </a>
                @endif
            </div>
        @endif

        @if(auth()->user()->isAdmin() && ! ($walkInReady ?? false))
            <div class="new-alert">
                <span class="new-alert-icon">⚠️</span>
                <div class="new-alert-text">
                    <strong>Walk-in and Add service are not turned on yet</strong>
                    <span>Run <code>php artisan migrate</code> in the project folder, then refresh this page.</span>
                </div>
            </div>
        @endif

        {{-- New-request indicator (same number as the red badge in the menu) --}}
        @if($historyOnly ?? false)
            <p class="date-filter-note">
                ✅ Showing <strong>completed and past bookings</strong>, newest first
                (<strong>{{ $appointments->total() }}</strong> {{ $appointments->total() === 1 ? 'booking' : 'bookings' }}).
            </p>
        @endif

        @if(! $showArchived && ! ($historyOnly ?? false))
            @if($newCount > 0)
                <div class="new-alert">
                    <span class="new-alert-icon">🔔</span>
                    <div class="new-alert-text">
                        <strong>{{ $newCount }} new {{ $newCount === 1 ? 'appointment' : 'appointments' }} to review</strong>
                        <span>Pending requests are marked <b class="new-pill">NEW</b> below.</span>
                    </div>
                    @if($pendingOnly)
                        <a class="new-alert-btn" href="{{ route('admin.appointments') }}">Show all</a>
                    @else
                        <a class="new-alert-btn" href="{{ route('admin.appointments', ['status' => 'pending']) }}">Show only new</a>
                    @endif
                </div>
            @else
                <div class="new-alert none">✅ No new appointments waiting for review.</div>
            @endif
        @endif

        @if($errors->any())
            <p style="color:#d64b6b;">{{ $errors->first() }}</p>
        @endif

        {{-- Pick a day to see only the bookings on it --}}
        <form method="GET" action="{{ route('admin.appointments') }}" class="date-filter">
            @if($showArchived)
                <input type="hidden" name="archived" value="1">
            @endif
            @if($historyOnly ?? false)
                <input type="hidden" name="view" value="history">
            @endif
            <label for="filter-date">📅 Show bookings on</label>
            <input type="date" id="filter-date" name="date" value="{{ $selectedDate }}"
                   onchange="this.form.submit()">
            <a class="date-filter-btn"
               href="{{ route('admin.appointments', array_filter(['archived' => $showArchived ? 1 : null, 'view' => ($historyOnly ?? false) ? 'history' : null, 'date' => now()->toDateString()])) }}">Today</a>
            @if($selectedDate)
                <a class="date-filter-btn clear"
                   href="{{ route('admin.appointments', array_filter(['archived' => $showArchived ? 1 : null, 'view' => ($historyOnly ?? false) ? 'history' : null])) }}">✕ Show all dates</a>
            @endif
        </form>

        @if($selectedDate)
            <p class="date-filter-note">
                Showing <strong>{{ $appointments->total() }}</strong>
                {{ $appointments->total() === 1 ? 'booking' : 'bookings' }} on
                <strong>{{ \Carbon\Carbon::parse($selectedDate)->format('l, M d, Y') }}</strong>
            </p>
        @endif

        <div class="table-wrap">

            <table>

                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Client</th>
                        <th>Service</th>
                        <th>Price</th>
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

                    @php
                        $clientTier = \App\Support\ClientTier::for($appointment->user);

                        // everything the "Details" window shows for this request
                        $details = [
                            'client'     => $appointment->user->full_name,
                            'email'      => $appointment->user->is_walk_in ? 'Walk-in (no account)' : $appointment->user->email,
                            'phone'      => $appointment->user->phone ?: '-',
                            'level'      => $clientTier->name() . ' (' . $clientTier->completed . ' completed ' . ($clientTier->completed === 1 ? 'visit' : 'visits') . ')',
                            'cancelled'  => (int) $appointment->user->cancel_strikes . ' of ' . config('loyalty.cancel_ban_limit', 3),
                            'service'    => $appointment->service->name . $appointment->extra_items->map(fn ($e) => ' + ' . $e->service->name)->implode(''),
                            'category'   => $appointment->service->category ?: '-',
                            'price'      => $appointment->extra_items->isEmpty()
                                ? $appointment->service->display_price
                                : '₱' . number_format($appointment->total_price, 2) . ' total (' . $appointment->service->display_price . $appointment->extra_items->map(fn ($e) => ' + ₱' . number_format((float) $e->price, 0))->implode('') . ')',
                            'duration'   => $appointment->service->duration_minutes ? $appointment->service->duration_minutes . ' minutes' : '-',
                            'date'       => $appointment->appointment_date->format('l, M d, Y'),
                            'time'       => \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A'),
                            'booked'     => $appointment->created_at->format('M d, Y, h:i A'),
                            'provider'   => $appointment->provider_label . ($appointment->with_owner && ! $appointment->staff_id ? ' (requested)' : ''),
                            'status'     => ucfirst($appointment->status),
                            'notes'      => trim((string) $appointment->notes) !== '' ? $appointment->notes : '',
                        ];
                    @endphp

                    <tr>

                        <td>
                            {{ $appointment->appointment_date->format('M d, Y') }}<br>
                            {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}
                            @if($appointment->status === 'pending' && ! $appointment->archived_at && $appointment->appointment_date->gte(today()))
                                <br><span class="new-pill">NEW</span>
                            @endif
                            @if($appointment->is_walk_in)
                                <br><span class="walkin-pill">WALK-IN</span>
                            @endif<br>

                            <button type="button"
                                    class="details-button"
                                    data-details="{{ json_encode($details) }}">
                                View details
                            </button>

                            @php
                                $isAdminUser = auth()->user()->isAdmin();
                                $receiptUrl  = $isAdminUser ? route('admin.appointments.receipt', $appointment) : route('staff.appointments.receipt', $appointment);
                                $packageUrl  = $isAdminUser ? route('admin.appointments.package', $appointment) : route('staff.appointments.package', $appointment);
                            @endphp

                            {{-- a package prints one receipt per finished session (see the Service column) --}}
                            @if($appointment->status === 'completed' && ! $appointment->is_package)
                                <a class="print-link" href="{{ $receiptUrl }}" target="_blank" rel="noopener">🧾 Print receipt</a>
                            @endif

                            @if($appointment->service->is_package && $appointment->status !== 'cancelled' && ! $appointment->archived_at)
                                <a class="print-link" href="{{ $packageUrl }}" target="_blank" rel="noopener">📋 Package sheet</a>
                            @endif
                        </td>

                        <td>
                            {{ $appointment->user->full_name }}<br>
                            <small>{{ $appointment->user->phone }}</small>
                        </td>

                        <td>
                            {{ $appointment->service->name }}

                            {{-- services added to this same visit --}}
                            @foreach($appointment->extra_items as $extra)
                                <br><small class="extra-line">+ {{ $extra->service->name }}</small>
                            @endforeach

                            @if(auth()->user()->isAdmin() && ($walkInReady ?? false) && ! $appointment->is_package && ! $appointment->archived_at && ($appointment->canChangeExtras() || $appointment->extra_items->isNotEmpty()))
                                @php
                                    $extrasData = [
                                        'title'    => $appointment->user->full_name . ' · ' . $appointment->service->name,
                                        'addUrl'   => route('admin.appointments.extras.add', $appointment),
                                        'canEdit'  => $appointment->canChangeExtras(),
                                        'main'     => ['name' => $appointment->service->name, 'price' => (float) $appointment->service->price],
                                        'extras'   => $appointment->extra_items->map(fn ($e) => [
                                            'name'      => $e->service->name,
                                            'price'     => (float) $e->price,
                                            'removeUrl' => route('admin.appointments.extras.remove', $e),
                                        ])->values(),
                                    ];
                                @endphp
                                <br>
                                <button type="button" class="extras-button" data-extras="{{ json_encode($extrasData) }}">
                                    {{ $appointment->canChangeExtras() ? '＋ Add service' : 'View services' }}
                                </button>
                            @endif

                            @if($appointment->is_package)
                                @include('partials.package-sessions', ['appointment' => $appointment])
                            @endif
                        </td>

                        {{-- price in its own column (total of the visit when services were added) --}}
                        <td class="price-cell">
                            @if($appointment->extra_items->isNotEmpty())
                                <strong>₱{{ number_format($appointment->total_price, 2) }}</strong>
                                <br><small>{{ $appointment->extra_items->count() + 1 }} services</small>
                            @else
                                {{ $appointment->service->display_price }}
                            @endif
                        </td>

                        <td>
                            <form method="POST"
                                  action="{{ auth()->user()->isAdmin() ? route('admin.appointments.update', $appointment) : route('staff.appointments.update', $appointment) }}"
                                  class="table-form"
                                  data-save-form="{{ $appointment->id }}">
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
                                <div class="save-actions">
                                    <button class="small-button">Save</button>

                                    {{-- red = not saved yet (every row starts like this), green = this row was saved --}}
                                    @if($appointment->admin_saved_at)
                                        <button type="button" class="save-state is-saved" data-saved="1" title="Saved">✓ Saved</button>

                                        {{-- takes the Saved mark off this row only --}}
                                        <button type="submit"
                                                class="unsave-button"
                                                formnovalidate
                                                formaction="{{ auth()->user()->isAdmin() ? route('admin.appointments.unsave', $appointment) : route('staff.appointments.unsave', $appointment) }}">
                                            Unsave
                                        </button>
                                    @else
                                        <button type="button" class="save-state is-unsaved" data-saved="0" title="Not saved yet. Click Save on this row">✕ Unsaved</button>
                                    @endif
                                </div>
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
                        <td colspan="{{ auth()->user()->isAdmin() ? 8 : 7 }}">
                            {{ $selectedDate ? 'No bookings on this date.' : ($showArchived ? 'No archived appointments.' : 'No appointments found.') }}
                        </td>
                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

        <div class="pagination">{{ $appointments->links() }}</div>

    </div>

</section>

{{-- ===== DETAILS WINDOW: what the client asked for ===== --}}
<div id="details-modal" class="details-modal" hidden>
    <div class="details-backdrop" data-close></div>

    <div class="details-card" role="dialog" aria-modal="true" aria-labelledby="details-title">

        <button type="button" class="details-close" data-close aria-label="Close">&times;</button>

        <span class="eyebrow">APPOINTMENT REQUEST</span>
        <h2 id="details-title">Request details</h2>

        <div class="details-group">
            <h3>Client</h3>
            <dl>
                <dt>Name</dt><dd data-field="client"></dd>
                <dt>Email</dt><dd data-field="email"></dd>
                <dt>Phone</dt><dd data-field="phone"></dd>
                <dt>Level</dt><dd data-field="level"></dd>
                <dt>Cancelled</dt><dd data-field="cancelled"></dd>
            </dl>
        </div>

        <div class="details-group">
            <h3>Service</h3>
            <dl>
                <dt>Service</dt><dd data-field="service"></dd>
                <dt>Type</dt><dd data-field="category"></dd>
                <dt>Price</dt><dd data-field="price"></dd>
                <dt>Duration</dt><dd data-field="duration"></dd>
            </dl>
        </div>

        <div class="details-group">
            <h3>Request</h3>
            <dl>
                <dt>Date</dt><dd data-field="date"></dd>
                <dt>Time</dt><dd data-field="time"></dd>
                <dt>Booked on</dt><dd data-field="booked"></dd>
                <dt>Serve by</dt><dd data-field="provider"></dd>
                <dt>Status</dt><dd data-field="status"></dd>
            </dl>
        </div>

        <div class="details-group">
            <h3>Client notes</h3>
            <p class="details-notes" data-field="notes"></p>
        </div>

    </div>
</div>

<style>
    .new-alert {
        display:flex; align-items:center; gap:14px; flex-wrap:wrap;
        margin:0 0 16px; padding:14px 18px; border-radius:16px;
        background:rgba(229,56,59,.10); border:1px solid rgba(229,56,59,.45); color:var(--heading);
    }
    .new-alert.none { background:var(--surface-soft); border-color:var(--line); font-weight:600; }
    .new-alert-icon { font-size:1.4rem; }
    .new-alert-text { display:flex; flex-direction:column; flex:1 1 220px; }
    .new-alert-btn {
        padding:9px 16px; border-radius:999px; background:#e5383b; color:#fff;
        font-weight:700; text-decoration:none;
    }
    .new-pill {
        display:inline-block; margin-top:4px; padding:2px 9px; border-radius:999px;
        background:#e5383b; color:#fff; font-size:11px; font-weight:800; letter-spacing:.06em;
    }
</style>

<style>
    .price-cell { white-space:nowrap; font-weight:700; color:var(--heading); }
    .price-cell small { font-weight:500; color:var(--mauve); }
    .date-filter { display:flex; align-items:center; gap:10px; flex-wrap:wrap; margin-bottom:14px; }
    .date-filter label { font-weight:700; color:var(--heading); }
    .date-filter input[type="date"] {
        padding:10px 12px; border:1px solid var(--line); border-radius:12px;
        background:var(--surface); color:var(--heading); font:inherit;
    }
    .date-filter-btn {
        padding:10px 16px; border-radius:12px; border:1px solid var(--line);
        background:var(--surface-soft); color:var(--heading); font-weight:700; text-decoration:none;
    }
    .date-filter-btn.clear { background:var(--mauve); color:#fff; border-color:transparent; }
    .date-filter-note { margin:0 0 14px; color:var(--heading); }
    html.dark-mode .date-filter input[type="date"] { color-scheme: dark; }
</style>

<style>
    .details-button {
        margin-top: 8px;
        padding: 6px 12px;
        border: 1px solid var(--line);
        border-radius: 999px;
        background: var(--surface-soft);
        color: var(--heading);
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
    }
    .details-button:hover { background: var(--mauve); color: #fff; border-color: var(--mauve); }

    .details-modal { position: fixed; inset: 0; z-index: 200; display: flex; align-items: center; justify-content: center; padding: 16px; }
    .details-modal[hidden] { display: none; }
    .details-backdrop { position: absolute; inset: 0; background: rgba(20, 10, 16, .6); }

    .details-card {
        position: relative;
        width: min(560px, 100%);
        max-height: calc(100vh - 32px);
        overflow-y: auto;
        padding: 26px 24px 24px;
        border-radius: 24px;
        background: var(--surface);
        border: 1px solid var(--line);
        box-shadow: 0 24px 60px rgba(0, 0, 0, .35);
    }
    .details-card h2 { margin: 4px 0 14px; color: var(--heading); }
    .print-link {
        display: block;
        margin-top: 6px;
        padding: 6px 10px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 700;
        text-align: center;
        text-decoration: none;
        color: #62444D;
        background: linear-gradient(135deg, #F3BCD2, #FFAFF1);
        white-space: nowrap;
    }
    .print-link:hover { filter: brightness(1.05); }

    .details-card h3 { margin: 0 0 6px; font-size: 12px; letter-spacing: 1.5px; text-transform: uppercase; color: var(--mauve); }

    .details-close {
        position: absolute; top: 12px; right: 14px;
        width: 40px; height: 40px;
        border: 0; border-radius: 50%;
        background: var(--surface-soft); color: var(--heading);
        font-size: 24px; line-height: 1; cursor: pointer;
    }

    .details-group { padding: 12px 0; border-top: 1px solid var(--line); }
    .details-group dl { display: grid; grid-template-columns: 110px minmax(0, 1fr); gap: 6px 12px; margin: 0; }
    .details-group dt { color: var(--text-muted, #7d7074); font-size: 13px; }
    .details-group dd { margin: 0; font-weight: 600; word-break: break-word; }
    .details-notes { margin: 0; white-space: pre-wrap; word-break: break-word; }
    .details-notes.is-empty { color: var(--text-muted, #7d7074); font-style: italic; }

    .save-actions { display: flex; gap: 8px; align-items: center; flex-wrap: wrap; }

    .save-state {
        padding: 9px 14px;
        border: 0;
        border-radius: 12px;
        font-size: 13px;
        font-weight: 700;
        color: #fff;
        cursor: default;
        transition: background .2s ease, transform .2s ease;
    }
    .save-state.is-saved   { background: #2e8b57; }
    .save-state.is-unsaved { background: #e5484d; cursor: pointer; }
    .save-state.is-unsaved:hover { background: #c93a3f; }
    .unsave-button {
        padding: 9px 14px;
        border: 0;
        border-radius: 12px;
        font-size: 13px;
        font-weight: 700;
        color: #fff;
        background: #e5484d;
        cursor: pointer;
    }
    .unsave-button:hover { background: #c93a3f; }

    .save-state.just-saved { animation: save-pop .8s ease; }

    @keyframes save-pop {
        0%   { transform: scale(1); }
        40%  { transform: scale(1.18); }
        100% { transform: scale(1); }
    }
</style>


@if(auth()->user()->isAdmin() && ($walkInReady ?? false))

{{-- ===== Add service to a visit ===== --}}
<div id="extras-modal" class="details-modal" hidden>
    <div class="details-backdrop" data-close-extras></div>

    <div class="details-card" role="dialog" aria-modal="true" aria-labelledby="extras-title">
        <button type="button" class="details-close" data-close-extras aria-label="Close">&times;</button>

        <span class="eyebrow">SAME VISIT</span>
        <h2 id="extras-title">Services in this visit</h2>
        <p class="muted" id="extras-sub"></p>

        <div class="details-group">
            <h3>Services</h3>
            <ul class="extras-list" id="extras-list"></ul>
            <div class="extras-total"><span>Total</span><strong id="extras-total"></strong></div>
        </div>

        <form method="POST" id="extras-add-form" class="details-group extras-add">
            @csrf
            <h3>Add another service</h3>
            <select name="service_id" required>
                <option value="">-- Choose a service --</option>
                @foreach($extraServices->groupBy('category') as $category => $items)
                    <optgroup label="{{ $category ?: 'Other' }}">
                        @foreach($items as $item)
                            <option value="{{ $item->id }}">{{ $item->name }} — ₱{{ number_format((float) $item->price, 0) }}</option>
                        @endforeach
                    </optgroup>
                @endforeach
            </select>
            <button class="primary-button" type="submit">Add to this visit</button>
            <p class="muted">Same client, same date and time. It does not count as a new booking.</p>
        </form>

        <p class="muted" id="extras-locked" hidden>This visit is closed, so its services can no longer be changed.</p>

        {{-- used by the little remove (x) buttons --}}
        <form method="POST" id="extras-remove-form" hidden>
            @csrf
            @method('DELETE')
        </form>
    </div>
</div>


{{-- ===== Walk-in ===== --}}
<div id="walkin-modal" class="details-modal" hidden>
    <div class="details-backdrop" data-close-walkin></div>

    <div class="details-card" role="dialog" aria-modal="true" aria-labelledby="walkin-title">
        <button type="button" class="details-close" data-close-walkin aria-label="Close">&times;</button>

        <span class="eyebrow">AT THE CLINIC</span>
        <h2 id="walkin-title">Add walk-in</h2>
        <p class="muted">For a customer who came in without booking online. Client level limits do not apply.</p>

        <form method="POST" action="{{ route('admin.appointments.walkin') }}" class="walkin-form">
            @csrf

            <div class="walkin-modes">
                <label><input type="radio" name="client_mode" value="existing" {{ old('client_mode', 'existing') === 'existing' ? 'checked' : '' }}> Registered client</label>
                <label><input type="radio" name="client_mode" value="new" {{ old('client_mode') === 'new' ? 'checked' : '' }}> No account</label>
            </div>

            <div data-mode="existing">
                <label>Client
                    <select name="user_id">
                        <option value="">-- Choose a client --</option>
                        @foreach($walkInClients as $c)
                            <option value="{{ $c->id }}" @selected(old('user_id') == $c->id)>{{ trim($c->first_name . ' ' . $c->last_name) }}{{ $c->phone ? ' · ' . $c->phone : '' }}</option>
                        @endforeach
                    </select>
                </label>
            </div>

            <div data-mode="new" hidden>
                <div class="walkin-grid">
                    <label>First name <input type="text" name="first_name" value="{{ old('first_name') }}" maxlength="100"></label>
                    <label>Last name <input type="text" name="last_name" value="{{ old('last_name') }}" maxlength="100"></label>
                </div>
                <label>Phone (optional)
                    <input type="tel" name="phone" value="{{ old('phone') }}" maxlength="11" inputmode="numeric" placeholder="09XXXXXXXXX"
                           oninput="this.value = this.value.replace(/\D/g, '').slice(0, 11);">
                </label>
            </div>

            <label>Service
                <select name="service_id" required>
                    <option value="">-- Choose a service --</option>
                    @foreach(\App\Models\Service::where('is_available', true)->orderBy('category')->orderBy('name')->get(['id', 'name', 'category', 'price'])->groupBy('category') as $category => $items)
                        <optgroup label="{{ $category ?: 'Other' }}">
                            @foreach($items as $item)
                                <option value="{{ $item->id }}" @selected(old('service_id') == $item->id)>{{ $item->name }} — ₱{{ number_format((float) $item->price, 0) }}</option>
                            @endforeach
                        </optgroup>
                    @endforeach
                </select>
            </label>

            <label>Served by
                <select name="provider">
                    <option value="any">Any available</option>
                    <option value="owner" @selected(old('provider') === 'owner')>The Owner</option>
                    @foreach($staff as $member)
                        <option value="{{ $member->id }}" @selected(old('provider') == $member->id)>{{ $member->user->full_name }}</option>
                    @endforeach
                </select>
            </label>

            <div class="walkin-grid">
                <label>Date <input type="date" name="appointment_date" value="{{ old('appointment_date', now()->toDateString()) }}" min="{{ now()->toDateString() }}" required></label>
                <label>Time <input type="time" name="appointment_time" value="{{ old('appointment_time', now()->format('H:i')) }}" required></label>
            </div>

            <label>Status
                <select name="status">
                    <option value="confirmed" @selected(old('status', 'confirmed') === 'confirmed')>Confirmed (customer is here)</option>
                    <option value="completed" @selected(old('status') === 'completed')>Completed (already done)</option>
                    <option value="pending"   @selected(old('status') === 'pending')>Pending</option>
                </select>
            </label>

            <label>Notes (optional)
                <textarea name="notes" rows="2" maxlength="1000">{{ old('notes') }}</textarea>
            </label>

            @if($errors->has('walk_in') || $errors->hasAny(['user_id','first_name','last_name','phone','service_id','appointment_date','appointment_time']))
                <p class="walkin-error">{{ $errors->first() }}</p>
            @endif

            <button class="primary-button full" type="submit">Save walk-in</button>
        </form>
    </div>
</div>

<style>
    .extras-button {
        margin-top: 6px; padding: 5px 12px; border-radius: 999px; cursor: pointer; font: inherit; font-size: 12px; font-weight: 700;
        border: 1px solid var(--line); background: var(--surface-soft); color: var(--heading);
    }
    .extras-button:hover { background: var(--mauve); color: #fff; border-color: var(--mauve); }
    .extra-line { color: var(--mauve); font-weight: 700; }
    .extra-total { color: var(--heading); font-weight: 800; }
    .walkin-pill {
        display:inline-block; margin-top:4px; padding:2px 9px; border-radius:999px;
        background:#2f8f6b; color:#fff; font-size:11px; font-weight:800; letter-spacing:.06em;
    }
    .extras-list { list-style:none; margin:0 0 8px; padding:0; }
    .extras-list li { display:flex; justify-content:space-between; align-items:center; gap:10px; padding:8px 0; border-bottom:1px dashed var(--line); }
    .extras-list li span.price { margin-left:auto; font-weight:700; }
    .extras-remove { border:0; background:transparent; color:#d64b6b; font-size:20px; line-height:1; cursor:pointer; }
    .extras-total { display:flex; justify-content:space-between; font-size:1.05rem; }
    .extras-add select, .walkin-form select, .walkin-form input, .walkin-form textarea { width:100%; margin:4px 0 10px; }
    .walkin-form label { display:block; font-weight:600; }
    .walkin-grid { display:grid; grid-template-columns:1fr 1fr; gap:12px; }
    .walkin-modes { display:flex; gap:18px; margin-bottom:10px; }
    .walkin-modes input { width:auto; margin:0 6px 0 0; }
    .walkin-error { color:#d64b6b; margin:0 0 10px; }
    .walkin-form [hidden] { display:none; }
    #walkin-modal .details-card { max-height: 92vh; overflow-y: auto; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {

    var peso = function (n) { return '₱' + Number(n).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 }); };

    function bindModal(modal, closeAttr) {
        function close() { modal.hidden = true; document.body.style.overflow = ''; }
        function open()  { modal.hidden = false; document.body.style.overflow = 'hidden'; }
        modal.querySelectorAll('[' + closeAttr + ']').forEach(function (el) { el.addEventListener('click', close); });
        document.addEventListener('keydown', function (e) { if (e.key === 'Escape' && !modal.hidden) { close(); } });
        return { open: open, close: close };
    }

    // ----- add service to a visit -----
    var extrasModal = document.getElementById('extras-modal');
    var extras = bindModal(extrasModal, 'data-close-extras');

    document.querySelectorAll('.extras-button').forEach(function (button) {
        button.addEventListener('click', function () {
            var d = {};
            try { d = JSON.parse(button.dataset.extras); } catch (e) {}

            document.getElementById('extras-sub').textContent = d.title || '';
            var list = document.getElementById('extras-list');
            list.innerHTML = '';
            var total = Number(d.main.price);

            function row(name, price, removeUrl) {
                var li = document.createElement('li');
                var n = document.createElement('span'); n.textContent = name;
                var p = document.createElement('span'); p.className = 'price'; p.textContent = peso(price);
                li.appendChild(n); li.appendChild(p);
                if (removeUrl && d.canEdit) {
                    var x = document.createElement('button');
                    x.type = 'button'; x.className = 'extras-remove'; x.title = 'Remove'; x.innerHTML = '&times;';
                    x.addEventListener('click', function () {
                        if (!confirm('Remove ' + name + ' from this visit?')) { return; }
                        var f = document.getElementById('extras-remove-form');
                        f.action = removeUrl;
                        f.submit();
                    });
                    li.appendChild(x);
                }
                list.appendChild(li);
            }

            row(d.main.name, d.main.price, null);
            (d.extras || []).forEach(function (e) { row('+ ' + e.name, e.price, e.removeUrl); total += Number(e.price); });
            document.getElementById('extras-total').textContent = peso(total);

            var addForm = document.getElementById('extras-add-form');
            addForm.action = d.addUrl;
            addForm.hidden = !d.canEdit;
            document.getElementById('extras-locked').hidden = !!d.canEdit;

            extras.open();
        });
    });

    // ----- walk-in -----
    var walkinModal = document.getElementById('walkin-modal');
    var walkin = bindModal(walkinModal, 'data-close-walkin');
    var openBtn = document.getElementById('open-walkin');
    if (openBtn) { openBtn.addEventListener('click', walkin.open); }

    function syncMode() {
        var mode = walkinModal.querySelector('input[name="client_mode"]:checked').value;
        walkinModal.querySelectorAll('[data-mode]').forEach(function (box) { box.hidden = box.dataset.mode !== mode; });
    }
    walkinModal.querySelectorAll('input[name="client_mode"]').forEach(function (r) { r.addEventListener('change', syncMode); });
    syncMode();

    @if($errors->has('walk_in') || $errors->hasAny(['user_id','first_name','last_name','phone','service_id','appointment_date','appointment_time']))
        walkin.open();
    @endif
});
</script>

@endif

<script>
document.addEventListener('DOMContentLoaded', function () {

    // ----- details window -----
    var modal = document.getElementById('details-modal');

    function closeDetails() { modal.hidden = true; document.body.style.overflow = ''; }

    document.querySelectorAll('.details-button').forEach(function (button) {
        button.addEventListener('click', function () {
            var data = {};
            try { data = JSON.parse(button.dataset.details); } catch (e) {}

            modal.querySelectorAll('[data-field]').forEach(function (el) {
                var value = data[el.dataset.field];

                if (el.dataset.field === 'notes') {
                    el.textContent = value ? value : 'The client did not add any notes.';
                    el.classList.toggle('is-empty', !value);
                } else {
                    el.textContent = value || '-';
                }
            });

            modal.hidden = false;
            document.body.style.overflow = 'hidden';
            modal.querySelector('.details-close').focus();
        });
    });

    modal.querySelectorAll('[data-close]').forEach(function (el) { el.addEventListener('click', closeDetails); });
    document.addEventListener('keydown', function (event) { if (event.key === 'Escape' && !modal.hidden) { closeDetails(); } });

    // ----- Saved / Unsaved buttons -----
    var justSaved = null;
    try { justSaved = sessionStorage.getItem('appointment-just-saved'); sessionStorage.removeItem('appointment-just-saved'); } catch (e) {}

    document.querySelectorAll('[data-save-form]').forEach(function (form) {

        // the form starts in one table cell and its fields sit in the next cells,
        // so look in the whole row (and use form.elements) instead of inside the form
        var row     = form.closest('tr');
        var pill    = row.querySelector('.save-state');
        var fields  = Array.prototype.filter.call(form.elements, function (el) { return el.tagName === 'SELECT'; });

        function changed() {
            return fields.some(function (field) {
                return Array.prototype.some.call(field.options, function (option) {
                    return option.selected !== option.defaultSelected;
                });
            });
        }

        var wasSaved = pill.dataset.saved === '1';

        function refresh() {
            // saved only when this row was saved AND nothing was changed since
            if (wasSaved && !changed()) {
                pill.className = 'save-state is-saved';
                pill.textContent = '✓ Saved';
                pill.title = 'Saved';
            } else {
                pill.className = 'save-state is-unsaved';
                pill.textContent = '✕ Unsaved';
                pill.title = 'Not saved yet. Click Save on this row';
            }
        }

        fields.forEach(function (field) { field.addEventListener('change', refresh); });

        form.addEventListener('submit', function () {
            try { sessionStorage.setItem('appointment-just-saved', form.dataset.saveForm); } catch (e) {}
        });

        if (justSaved && justSaved === form.dataset.saveForm) {
            pill.classList.add('just-saved');
        }
    });
});
</script>

@endsection
