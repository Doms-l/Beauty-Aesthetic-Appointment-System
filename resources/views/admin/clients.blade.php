@extends('layouts.app')

@section('title', 'Clients | M. Cares')

@section('content')

<section class="page-hero">
    <div class="container">
        <span class="eyebrow">ADMIN · CLIENTS</span>
        <h1>Clients</h1>
        <p>
            Clients are banned automatically after
            {{ config('loyalty.cancel_ban_limit', 3) }} cancelled appointments.
            Only an admin can unban an account.
        </p>
    </div>
</section>

<section class="section compact">
    <div class="container">

        <div class="dashboard-actions">
            <a class="{{ $onlyBanned ? 'secondary-button' : 'primary-button' }}"
               href="{{ route('admin.clients') }}">
                All clients
            </a>

            <a class="{{ $onlyBanned ? 'primary-button' : 'secondary-button' }}"
               href="{{ route('admin.clients', ['banned' => 1]) }}">
                Banned ({{ $bannedCount }})
            </a>
        </div>

        <form method="GET" action="{{ route('admin.clients') }}" class="form-stack" style="margin:16px 0;">
            @if ($onlyBanned)
                <input type="hidden" name="banned" value="1">
            @endif

            <input
                type="search"
                name="q"
                value="{{ $search }}"
                placeholder="Search name, email or phone"
            >
        </form>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Client</th>
                        <th>Contact</th>
                        <th>Level</th>
                        <th>Cancelled</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                @forelse ($clients as $client)
                    @php
                        $tier = \App\Support\ClientTier::for($client);
                    @endphp

                    <tr>
                        <td>{{ $client->full_name }}</td>

                        <td>
                            {{ $client->email }}<br>
                            <small>{{ $client->phone }}</small>
                        </td>

                        <td>{{ $tier->key === 'vip' ? '👑 ' : '' }}{{ $tier->name() }}</td>

                        <td>{{ $client->cancel_strikes }} / {{ config('loyalty.cancel_ban_limit', 3) }}</td>

                        <td>
                            @if ($client->isBanned())
                                <span class="status cancelled">Banned</span>
                                <br>
                                <small>{{ $client->banned_at->format('M d, Y h:i A') }}</small>
                            @else
                                <span class="status completed">Active</span>
                            @endif
                        </td>

                        <td>
                            @if ($client->isBanned())
                                <form method="POST" action="{{ route('admin.clients.unban', $client) }}"
                                      onsubmit="return confirm('Unban {{ $client->full_name }}? Their cancellation count will start again from 0.');">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="primary-button">Unban</button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('admin.clients.ban', $client) }}"
                                      onsubmit="return confirm('Ban {{ $client->full_name }}? They will be logged out and cannot log in until you unban them.');">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="secondary-button">Ban</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">No clients found.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination">{{ $clients->links() }}</div>

    </div>
</section>

@endsection
