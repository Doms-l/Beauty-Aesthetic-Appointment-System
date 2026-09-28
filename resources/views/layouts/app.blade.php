<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'M. Cares Beauty Services')</title>
<link rel="icon" type="image/png" href="{{ asset('images/round.png') }}?v=1">

@vite(['resources/css/app.css', 'resources/js/app.jsx'])
</head>
<body>
<header class="site-header">
    <div class="container nav-wrap">
        <a class="brand" href="{{ route('home') }}">
            <img src="{{ asset('images/logo.png') }}" alt="M. Cares Beauty Services logo">
            <span>M. CARES<br><small>BEAUTY SERVICES</small></span>
        </a>

        <nav class="main-nav">
            <a href="{{ route('home') }}">Home</a>
            <a href="{{ route('services.index') }}">Services</a>
            @auth
                @if(auth()->user()->isClient())
                    <a href="{{ route('client.appointments') }}">Appointments</a>
                    <a href="{{ route('client.profile') }}">Profile</a>
                    <a class="nav-cta" href="{{ route('client.appointments.create') }}">Book Now</a>
                @elseif(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}">Admin Dashboard</a>
                @elseif(auth()->user()->isStaff())
                    <a href="{{ route('staff.dashboard') }}">Staff Dashboard</a>
                @endif
                <form method="POST" action="{{ route('logout') }}" class="inline-form">
                    @csrf
                    <button class="link-button" type="submit">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}">Login</a>
                <a class="nav-cta" href="{{ route('register') }}">Register</a>
            @endauth
        </nav>
    </div>
</header>

@if(session('success'))
    <div class="container flash success">{{ session('success') }}</div>
@endif

@if($errors->any())
    <div class="container flash error">
        <strong>Please check the form.</strong>
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<main>
    @yield('content')
</main>

<footer class="site-footer">
    <div class="container footer-grid">
        <div>
            <h3>M. CARES</h3>
            <p>Beauty, care, and confidence in one place.</p>
        </div>
        <div>
            <p>Web-Based Aesthetic Clinic Appointment and Management System</p>
            <p>© {{ date('Y') }} M. Cares Beauty Services</p>
        </div>
    </div>
</footer>
</body>
</html>
