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

                <td>{{ $appointment->service->name }}</td>

                <td>
                    <span class="status {{ $appointment->status }}">{{ ucfirst($appointment->status) }}</span>
                </td>

                <td>
                    <div class="sa-actions">

                        <button type="button" class="sa-open" data-appointment="{{ json_encode($payload) }}">
                            {{ $payload['canEdit'] ? 'Manage' : 'View details' }}
                        </button>

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
