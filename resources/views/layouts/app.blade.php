<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >

    <meta
        name="color-scheme"
        content="light dark"
    >

    <title>
        @yield('title', 'M. Cares Beauty Services')
    </title>

    <link
        rel="icon"
        type="image/png"
        href="{{ asset('images/round.png') }}"
    >

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

<header class="site-header">

    <div class="container nav-wrap">

        {{-- BRAND / LOGO --}}

        <a
            class="brand"
            href="{{ route('home') }}"
        >

            <img
                src="{{ asset('images/logo.png') }}"
                alt="M. Cares Beauty Services logo"
            >

            <span>
                M. CARES<br>
                <small>BEAUTY SERVICES</small>
            </span>

        </a>


        {{-- NAVIGATION LINKS --}}

        <nav class="main-nav">

            <a href="{{ route('home') }}">
                Home
            </a>

            <a href="{{ route('services.index') }}">
                Services
            </a>


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

                    @csrf

                    <button
                        type="submit"
                        class="link-button"
                    >
                        Logout
                    </button>

                </form>


            @else

                {{-- GUEST --}}

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

        </nav>

    </div>

</header>


{{-- =========================================================
    SUCCESS MESSAGE
========================================================= --}}

@if(session('success'))

    <div class="container flash success">

        {{ session('success') }}

    </div>

@endif


{{-- =========================================================
    VALIDATION ERRORS
========================================================= --}}

@if($errors->any())

    <div class="container flash error">

        <strong>
            Please check the form.
        </strong>

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
    M. CARES CHATBOT
========================================================= --}}

<div class="mcares-chatbot">

    {{-- CHAT BUTTON --}}

    <button
        type="button"
        id="mcares-chat-toggle"
        class="mcares-chat-toggle"
        aria-label="Open M. Cares chatbot"
        aria-expanded="false"
    >
        💬
    </button>


    {{-- CHAT WINDOW --}}

    <div
        id="mcares-chat-window"
        class="mcares-chat-window"
        aria-hidden="true"
    >

        {{-- CHAT HEADER --}}

        <div class="mcares-chat-header">

            <div class="mcares-chat-header-info">

                <div class="mcares-chat-avatar">
    <img
        src="{{ asset('images/chatbot.png') }}"
        alt="M. Cares Assistant"
    >
</div>

                <div>

                    <strong>
                        M. Cares Assistant
                    </strong>

                    <small>
                        We're here to help
                    </small>

                </div>

            </div>


            <button
                type="button"
                id="mcares-chat-close"
                class="mcares-chat-close"
                aria-label="Close chatbot"
            >
                ×
            </button>

        </div>


        {{-- CHAT MESSAGES --}}

        <div
            id="mcares-chat-messages"
            class="mcares-chat-messages"
        >

            <div class="mcares-chat-message bot">

                <div class="mcares-chat-bubble">

                    Hi! 👋

                    <br><br>

                    Welcome to
                    <strong>M. Cares Beauty Services</strong>.

                    <br><br>

                    How can I help you today?

                </div>

            </div>

        </div>


        {{-- QUICK QUESTIONS --}}

        <div class="mcares-chat-quick">

            <button
                type="button"
                data-question="What services do you offer?"
            >
                Services
            </button>

            <button
                type="button"
                data-question="How can I book an appointment?"
            >
                Book Appointment
            </button>

            <button
                type="button"
                data-question="What amenities are available?"
            >
                Amenities
            </button>

            <button
                type="button"
                data-question="What are your clinic hours?"
            >
                Clinic Hours
            </button>

        </div>


        {{-- CHAT INPUT --}}

        <form
            id="mcares-chat-form"
            class="mcares-chat-form"
        >

            <input
                type="text"
                id="mcares-chat-input"
                placeholder="Type your question..."
                autocomplete="off"
                maxlength="500"
            >

            <button
                type="submit"
                aria-label="Send message"
            >
                Send
            </button>

        </form>

    </div>

</div>


{{-- =========================================================
    FOOTER
========================================================= --}}

<footer class="site-footer">

    <div class="container footer-grid">

        <div>

            <h3>
                M. CARES
            </h3>

            <p>
                Beauty, care, and confidence in one place.
            </p>

        </div>


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

    </div>

</footer>


</body>

</html>