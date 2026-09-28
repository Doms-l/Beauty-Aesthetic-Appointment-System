@extends('layouts.app')

@section('title', 'M. Cares Beauty Services')

@section('content')
<section class="hero-section">
    <div class="container hero-grid">
        <div class="hero-copy">
            <span class="eyebrow">BEAUTY · CARE · CONFIDENCE</span>
            <h1>Enhance your beauty.<br><em>Feel your best.</em></h1>
            <p>
                Discover personalized aesthetic and beauty services with an easy online appointment experience at M. Cares Beauty Services.
            </p>
            <div class="hero-actions">
                <a class="primary-button" href="{{ auth()->check() ? (auth()->user()->isClient() ? route('client.appointments.create') : route('home')) : route('register') }}">Book an Appointment</a>
                <a class="secondary-button" href="{{ route('services.index') }}">Explore Services</a>
            </div>
            <div class="hero-note"><span>✦</span> Professional service · Simple booking · Personalized care</div>
        </div>
        <div class="hero-art">
            <div class="logo-orbit orbit-one"></div>
            <div class="logo-orbit orbit-two"></div>
            <img src="{{ asset('images/logo.png') }}" alt="M. Cares logo">
        </div>
    </div>
</section>

<section class="section soft-section">
    <div class="container">
        <div class="section-heading">
            <span class="eyebrow">OUR SERVICES</span>
            <h2>Beauty services made for you.</h2>
            <p>Choose a service, select a preferred schedule, and send your appointment request online.</p>
        </div>
        <div class="card-grid three">
            @forelse($services as $service)
                <article class="service-card">
                    <div class="service-icon">✿</div>
                    <h3>{{ $service->name }}</h3>
                    <p>{{ $service->description }}</p>
                    <div class="service-meta">
                        <strong>₱{{ number_format($service->price, 2) }}</strong>
                        <span>{{ $service->duration_minutes }} min</span>
                    </div>
                </article>
            @empty
                <div class="empty-state">Services will appear here after the administrator adds them.</div>
            @endforelse
        </div>
        <div class="center-button"><a class="secondary-button" href="{{ route('services.index') }}">View all services</a></div>
    </div>
</section>

<section class="section">
    <div class="container feature-grid">
        <div>
            <span class="eyebrow">WHY M. CARES?</span>
            <h2>A simpler way to manage your beauty appointments.</h2>
        </div>
        <div class="feature-list">
            <div><span>01</span><div><h3>Easy registration</h3><p>Create one client account and keep your information ready for future appointments.</p></div></div>
            <div><span>02</span><div><h3>Online appointment requests</h3><p>Choose a service, preferred date, and time from the client dashboard.</p></div></div>
            <div><span>03</span><div><h3>Organized clinic management</h3><p>Staff and administrators can review schedules, clients, services, and appointment status.</p></div></div>
        </div>
    </div>
</section>
@endsection
