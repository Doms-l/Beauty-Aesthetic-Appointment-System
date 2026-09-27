@extends('layouts.app')

@section('title', 'Services | M. Cares')

@section('content')
<section class="page-hero">
    <div class="container"><span class="eyebrow">M. CARES SERVICES</span><h1>Beauty and aesthetic services</h1><p>Browse the services currently available at the clinic.</p></div>
</section>
<section class="section">
    <div class="container card-grid three">
        @forelse($services as $service)
            <article class="service-card large">
                <div class="service-icon">✿</div>
                <h2>{{ $service->name }}</h2>
                <p>{{ $service->description }}</p>
                <div class="service-meta"><strong>₱{{ number_format($service->price, 2) }}</strong><span>{{ $service->duration_minutes }} min</span></div>
                @auth
                    @if(auth()->user()->isClient())
                        <a class="secondary-button full" href="{{ route('client.appointments.create') }}">Book this service</a>
                    @endif
                @else
                    <a class="secondary-button full" href="{{ route('register') }}">Register to book</a>
                @endauth
            </article>
        @empty
            <div class="empty-state">No services are currently available.</div>
        @endforelse
    </div>
</section>
@endsection
