
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
<<<<<<< HEAD
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="color-scheme" content="light dark">

    <title>@yield('title', 'M. Cares Beauty Services')</title>

    <link rel="icon" type="image/png" href="{{ asset('images/round.png') }}">

    @viteReactRefresh
    @vite(['resources/css/app.css', 'resources/js/app.jsx'])
</head>

<body>

=======

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >

    {{-- Light/Dark mode support --}}
    <meta
        name="color-scheme"
        content="light dark"
    >

    <title>
        @yield('title', 'M. Cares Beauty Services')
    </title>

    {{-- Browser tab icon --}}
    <link
        rel="icon"
        type="image/png"
        href="{{ asset('images/round.png') }}?v=1"
    >

    {{-- Laravel Vite --}}
    @viteReactRefresh

@vite([
    'resources/css/app.css',
    'resources/js/app.jsx'
])

</head>


<body>


{{-- =========================================================
    NAVIGATION
========================================================= --}}

>>>>>>> main
<header class="site-header">

    <div class="container nav-wrap">

<<<<<<< HEAD
        <a class="brand" href="{{ route('home') }}">
            <img src="{{ asset('images/logo.png') }}" alt="M. Cares Beauty Services logo">
=======

        {{-- BRAND / LOGO --}}

        <a
            class="brand"
            href="{{ route('home') }}"
        >

            <img
                src="{{ asset('images/logo.png') }}"
                alt="M. Cares Beauty Services logo"
            >
>>>>>>> main

            <span>
                M. CARES<br>
                <small>BEAUTY SERVICES</small>
            </span>
<<<<<<< HEAD
=======

>>>>>>> main
        </a>


        {{-- NAVIGATION LINKS --}}

        <nav class="main-nav">

<<<<<<< HEAD
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
=======

            {{-- HOME --}}

            <a href="{{ route('home') }}">
                Home
            </a>


            {{-- SERVICES --}}

            <a href="{{ route('services.index') }}">
                Services
            </a>


            {{-- =================================================
                LOGGED-IN USER
            ================================================= --}}

            @auth


                {{-- CLIENT --}}

                @if(auth()->user()->isClient())

                    <a href="{{ route('client.appointments') }}">
                        Appointments
                    </a>

                    <a href="{{ route('client.profile') }}">
                        Profile
                    </a>

                    <a
                        class="nav-cta"
                        href="{{ route('client.appointments.create') }}"
                    >
                        Book Now
                    </a>


                {{-- ADMIN --}}

                @elseif(auth()->user()->isAdmin())

                    <a href="{{ route('admin.dashboard') }}">
                        Admin Dashboard
                    </a>


                {{-- STAFF --}}

                @elseif(auth()->user()->isStaff())

                    <a href="{{ route('staff.dashboard') }}">
                        Staff Dashboard
                    </a>

                @endif


                {{-- LOGOUT --}}

                <form
                    method="POST"
                    action="{{ route('logout') }}"
                    class="inline-form"
                >

>>>>>>> main
                    @csrf

                    <button
                        type="submit"
                        class="link-button"
                    >
                        Logout
                    </button>

                </form>

<<<<<<< HEAD
            @else

                <a href="{{ route('login') }}">Login</a>
                <a class="nav-cta" href="{{ route('register') }}">Register</a>

            @endauth

=======

            {{-- =================================================
                LOGGED-OUT USER
            ================================================= --}}

            @else

                <a href="{{ route('login') }}">
                    Login
                </a>

                <a
                    class="nav-cta"
                    href="{{ route('register') }}"
                >
                    Register
                </a>

            @endauth


>>>>>>> main
        </nav>

    </div>

</header>



{{-- =========================================================
    SUCCESS MESSAGE
========================================================= --}}

@if(session('success'))
<<<<<<< HEAD
    <div class="container flash success">
        {{ session('success') }}
    </div>
=======

    <div class="container flash success">

        {{ session('success') }}

    </div>

>>>>>>> main
@endif



{{-- =========================================================
    VALIDATION ERRORS
========================================================= --}}

@if($errors->any())

    <div class="container flash error">
<<<<<<< HEAD
        <strong>Please check the form.</strong>
=======

        <strong>
            Please check the form.
        </strong>
>>>>>>> main

        <ul>

            @foreach($errors->all() as $error)

                <li>
                    {{ $error }}
                </li>

            @endforeach

        </ul>

    </div>

@endif



{{-- =========================================================
    MAIN PAGE CONTENT
========================================================= --}}

<main>

    @yield('content')

</main>



{{-- =========================================================
    FOOTER
========================================================= --}}

<footer class="site-footer">

    <div class="container footer-grid">

<<<<<<< HEAD
=======

>>>>>>> main
        <div>

            <h3>
                M. CARES
            </h3>

            <p>
                Beauty, care, and confidence in one place.
            </p>

        </div>

<<<<<<< HEAD
=======

>>>>>>> main
        <div>

            <p>
                Web-Based Aesthetic Clinic Appointment and
                Management System
            </p>

            <p>
                © {{ date('Y') }}
                M. Cares Beauty Services
            </p>

        </div>

<<<<<<< HEAD
=======

>>>>>>> main
    </div>

</footer>

<<<<<<< HEAD
=======

>>>>>>> main
</body>
</html>