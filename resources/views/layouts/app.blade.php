@php
    use Illuminate\Support\Facades\Storage;
@endphp

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

                {{-- =================================================
                    CLIENT NAVIGATION
                ================================================= --}}

                @if(auth()->user()->isClient())

                    <a href="{{ route('client.appointments') }}">
                        Appointments
                    </a>

                    <a
                        class="nav-cta"
                        href="{{ route('client.appointments.create') }}"
                    >
                        Book Now
                    </a>


                    {{-- PROFILE PICTURE --}}

                    <a
                        href="{{ route('client.profile') }}"
                        class="nav-profile"
                        title="Edit Profile"
                        aria-label="Edit Profile"
                    >

                        @if(auth()->user()->profile_picture)

                            <img
                                src="{{ Storage::disk('public')->url(auth()->user()->profile_picture) }}"
                                alt="Profile Picture"
                            >

                        @else

                            <span>
                                {{ strtoupper(substr(auth()->user()->first_name, 0, 1)) }}
                            </span>

                        @endif

                    </a>


                {{-- =================================================
                    ADMIN NAVIGATION
                ================================================= --}}

                @elseif(auth()->user()->isAdmin())

                    <a href="{{ route('admin.dashboard') }}">
                        Admin Dashboard
                    </a>

                    {{-- ADMIN PROFILE PICTURE --}}

                    <a
                        href="{{ route('admin.profile') }}"
                        class="nav-profile"
                        title="Admin Profile"
                        aria-label="Admin Profile"
                    >

                        @if(auth()->user()->profile_picture)

                            <img
                                src="{{ Storage::disk('public')->url(auth()->user()->profile_picture) }}"
                                alt="Admin Profile Picture"
                            >

                        @else

                            <span>
                                {{ strtoupper(substr(auth()->user()->first_name, 0, 1)) }}
                            </span>

                        @endif

                    </a>


                {{-- =================================================
                    STAFF NAVIGATION
                ================================================= --}}

                @elseif(auth()->user()->isStaff())

                    <a href="{{ route('staff.dashboard') }}">
                        Staff Dashboard
                    </a>

                @endif


                {{-- =================================================
                    LOGOUT
                ================================================= --}}

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

                {{-- =================================================
                    GUEST NAVIGATION
                ================================================= --}}

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

        <img
            src="{{ asset('images/chatbot.png') }}"
            alt="M. Cares Chatbot"
            class="mcares-chat-toggle-img"
        >

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

    <div class="footer-container">

        {{-- LEFT: LOGO --}}
        <div class="footer-brand">

            <img
                src="{{ asset('images/round.png') }}"
                alt="M. Cares Beauty Services"
                class="footer-logo"
            >

        </div>


        {{-- CENTER: CONTACT INFORMATION --}}
        <div class="footer-contact">

            {{-- FACEBOOK --}}
            <a href="https://www.facebook.com/MacaylaCares"
               target="_blank"
               rel="noopener noreferrer"
               class="footer-contact-item">

                <span class="footer-icon">f</span>

                <span>Macayla Cares</span>

            </a>


            {{-- PHONE --}}
            <a href="tel:09155168312"
               class="footer-contact-item">

                <span class="footer-icon">☎</span>

                <span>09155168312</span>

            </a>

        </div>


        {{-- RIGHT: COPYRIGHT --}}
        <div class="footer-copyright">

            <p>
                © {{ date('Y') }} M. Cares Beauty Services.
                All rights reserved.
            </p>

        </div>

    </div>

</footer>
<style>

/* =========================================================
   FOOTER
   ========================================================= */

.site-footer {
    background: #222222;
    color: #ffffff;
    padding: 28px 0;
}


.footer-container {
    width: min(1200px, 92%);
    margin: 0 auto;

    display: flex;
    align-items: center;
    justify-content: space-between;

    gap: 30px;
}


/* LOGO */

.footer-brand {
    display: flex;
    align-items: center;
}


.footer-logo {
    width: 75px;
    height: 75px;

    object-fit: contain;

    display: block;
}


/* CONTACT */

.footer-contact {
    display: flex;
    align-items: center;
    gap: 25px;
}


.footer-contact-item {
    display: flex;
    align-items: center;
    gap: 9px;

    color: #ffffff;
    text-decoration: none;

    font-size: 15px;

    transition: 0.3s ease;
}


.footer-contact-item:hover {
    color: #e91e8c;
}


/* ICONS */

.footer-icon {
    width: 32px;
    height: 32px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 50%;

    background: #e91e8c;
    color: #ffffff;

    font-weight: bold;
    font-size: 18px;
}


/* COPYRIGHT */

.footer-copyright p {
    margin: 0;

    font-size: 13px;

    color: #cccccc;

    text-align: right;
}


/* MOBILE */

@media (max-width: 700px) {

    .footer-container {
        flex-direction: column;
        text-align: center;
    }


    .footer-contact {
        flex-direction: column;
        gap: 15px;
    }


    .footer-copyright p {
        text-align: center;
    }

}

</style>


</body>

</html>