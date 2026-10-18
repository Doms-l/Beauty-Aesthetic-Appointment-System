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

<style>
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

<script>
document.addEventListener('DOMContentLoaded', function () {

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
