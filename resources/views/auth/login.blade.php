@extends('layouts.app')

@section('title', 'Login | M. Cares')

@section('content')

<div class="auth-page">
    <div class="auth-card">

        <div class="auth-logo">
            <img
                src="{{ asset('images/logo.png') }}"
                alt="M. Cares logo"
            >
        </div>

        <span class="eyebrow">WELCOME BACK</span>

        <h1>Login to your account</h1>

        <p class="muted">
            Manage your appointments and profile from one place.
        </p>

        @if ($errors->any())
            <div class="form-error">
                {{ $errors->first() }}
            </div>
        @endif

        <form
            method="POST"
            action="{{ route('login.store') }}"
            class="form-stack"
            id="loginForm"
        >
            @csrf

            {{-- Email --}}
            <label>
                Email address

                <input
                    type="email"
                    name="email"
                    id="loginEmail"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    autocomplete="username"
                >
            </label>

            {{-- Password --}}
            <label>
                Password

                <div class="password-input-wrapper">

                    <input
                        type="password"
                        name="password"
                        id="loginPassword"
                        required
                        autocomplete="current-password"
                    >

                    <button
                        type="button"
                        class="password-toggle"
                        id="loginPasswordToggle"
                        aria-label="Show password"
                        title="Show password"
                    >
                        👁
                    </button>

                </div>
            </label>

            {{-- Remember Me --}}
            <label class="check-row">
                <input
                    type="checkbox"
                    name="remember"
                    value="1"
                    id="rememberMe"
                >
                Remember me
            </label>

            <button
                class="primary-button full"
                type="submit"
            >
                Login
            </button>

        </form>

        <p class="auth-bottom">
            Don't have an account?
            <a href="{{ route('register') }}">Create one</a>
        </p>

        <div class="demo-box">
            <strong>Demo admin</strong><br>
            admin@mcares.test<br>
            Admin@12345<br>
            <small>
                Change this password before real deployment.
            </small>
        </div>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const emailInput = document.getElementById('loginEmail');
    const passwordInput = document.getElementById('loginPassword');
    const passwordToggle = document.getElementById('loginPasswordToggle');
    const rememberCheckbox = document.getElementById('rememberMe');
    const loginForm = document.getElementById('loginForm');

    // Show / Hide Password
    passwordToggle.addEventListener('click', function () {

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            passwordToggle.textContent = '🙈';
            passwordToggle.setAttribute('aria-label', 'Hide password');
            passwordToggle.setAttribute('title', 'Hide password');
        } else {
            passwordInput.type = 'password';
            passwordToggle.textContent = '👁';
            passwordToggle.setAttribute('aria-label', 'Show password');
            passwordToggle.setAttribute('title', 'Show password');
        }

    });

    // Restore Remember Me Settings
    const savedEmail = localStorage.getItem('mcares_remembered_email');
    const savedRemember = localStorage.getItem('mcares_remember_me');

    if (savedEmail && !emailInput.value) {
        emailInput.value = savedEmail;
    }

    if (savedRemember === 'true') {
        rememberCheckbox.checked = true;
    }

    // Save Email and Remember Me Choice
    loginForm.addEventListener('submit', function () {

        if (rememberCheckbox.checked) {

            localStorage.setItem(
                'mcares_remembered_email',
                emailInput.value
            );

            localStorage.setItem(
                'mcares_remember_me',
                'true'
            );

        } else {

            localStorage.removeItem('mcares_remembered_email');
            localStorage.removeItem('mcares_remember_me');

        }

    });

});
</script>

@endsection

