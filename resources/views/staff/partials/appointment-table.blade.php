{{-- Rows of appointments for the staff pages.
     $rows  : collection of Appointment
     $staff : the logged-in staff profile (or null)
     $empty : text shown when there is nothing --}}

<div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>Date &amp; time</th>
                <th>Client</th>
                <th>Service</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>
        @forelse($rows as $appointment)

            @php $payload = \App\Support\StaffAppointments::payload($appointment, $staff); @endphp

            <tr>
                <td>
                    {{ $appointment->appointment_date->format('M d, Y') }}<br>
                    <strong>{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</strong>
                </td>

                <td>
                    {{ $appointment->user->full_name }}<br>
                    <small>{{ $appointment->user->phone }}</small>
                </td>

                <td>
                    {{ $appointment->service->name }}

                    @if($appointment->is_package && \App\Support\StaffAppointments::isMine($appointment, $staff))
                        @include('partials.package-sessions', ['appointment' => $appointment])
                    @endif
                </td>

                <td>
                    <span class="status {{ $appointment->status }}">{{ ucfirst($appointment->status) }}</span>
                </td>

                <td>
                    <div class="sa-actions">

                        <button type="button" class="sa-open" data-appointment="{{ json_encode($payload) }}">
                            {{ $payload['canEdit'] ? 'Manage' : 'View details' }}
                        </button>

                        @if(\App\Support\StaffAppointments::isMine($appointment, $staff))
                            @if($appointment->status === 'completed' && ! $appointment->is_package)
                                <a class="sa-print" href="{{ route('staff.appointments.receipt', $appointment) }}" target="_blank" rel="noopener">🧾 Print receipt</a>
                            @endif

                            @if($appointment->service->is_package && $appointment->status !== 'cancelled')
                                <a class="sa-print" href="{{ route('staff.appointments.package', $appointment) }}" target="_blank" rel="noopener">📋 Package sheet</a>
                            @endif
                        @endif

                        @if($payload['canClaim'])
                            <form method="POST" action="{{ $payload['claimUrl'] }}" class="inline-form">
                                @csrf
                                @method('PATCH')
                                <button class="sa-claim"
                                        onclick="return confirm('Take this appointment? It will be assigned to you.')">
                                    Take it
                                </button>
                            </form>
                        @endif

                    </div>
                </td>
            </tr>

        @empty

            <tr>
                <td colspan="5">{{ $empty ?? 'Nothing here.' }}</td>
            </tr>

        @endforelse
        </tbody>
    </table>
</div>


<style>
    .sa-print {
        display: inline-block;
        margin-top: 6px;
        padding: 6px 12px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 700;
        text-decoration: none;
        color: #62444D;
        background: linear-gradient(135deg, #F3BCD2, #FFAFF1);
        white-space: nowrap;
    }
    .sa-print:hover { filter: brightness(1.05); }
</style>
