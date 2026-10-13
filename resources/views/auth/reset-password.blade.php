@extends('layouts.app')

@section('title', 'Enter Code | M. Cares')

@section('content')

<div class="auth-page">
    <div class="auth-card">

        <div class="auth-logo">
            <img src="{{ asset('images/logo.png') }}" alt="M. Cares logo">
        </div>

        <span class="eyebrow">CHECK YOUR EMAIL</span>

        <h1>Enter your code</h1>

        <p class="muted">
            We sent a 6-digit code to <strong>{{ $maskedEmail }}</strong>.
            It expires in {{ \App\Support\VerificationCodes::TTL_MINUTES }} minutes.
        </p>

        <form method="POST" action="{{ route('password.update') }}" class="form-stack">
            @csrf

            <label>
                6-digit code

                <input
                    type="text"
                    name="code"
                    value="{{ old('code') }}"
                    required
                    autofocus
                    maxlength="6"
                    inputmode="numeric"
                    pattern="[0-9]{6}"
                    autocomplete="one-time-code"
                    placeholder="000000"
                    style="letter-spacing: 8px; font-size: 1.4rem; text-align: center;"
                    oninput="this.value = this.value.replace(/\D/g, '').slice(0, 6);"
                >
            </label>

            <label>
                New password

                <div class="password-input-wrapper">
                    <input
                        id="password"
                        type="password"
                        name="password"
                        required
                        minlength="8"
                        autocomplete="new-password"
                    >

                    <button
                        type="button"
                        class="password-toggle"
                        onclick="togglePassword('password', this)"
                        aria-label="Show password"
                    >
                        👁
                    </button>
                </div>
            </label>

            <label>
                Confirm new password

                <div class="password-input-wrapper">
                    <input
                        id="password_confirmation"
                        type="password"
                        name="password_confirmation"
                        required
                        minlength="8"
                        autocomplete="new-password"
                    >

                    <button
                        type="button"
                        class="password-toggle"
                        onclick="togglePassword('password_confirmation', this)"
                        aria-label="Show password"
                    >
                        👁
                    </button>
                </div>
            </label>

            <p class="form-help">
                Password must contain at least 8 characters, including an uppercase letter,
                lowercase letter, number, and special character.
            </p>

            <button class="primary-button full" type="submit">
                Change Password
            </button>
        </form>

        <form method="POST" action="{{ route('password.resend') }}" class="auth-bottom">
            @csrf
            Didn't get the code?
            <button
                type="submit"
                style="background:none;border:0;padding:0;font:inherit;color:inherit;font-weight:700;text-decoration:underline;cursor:pointer;"
            >
                Send a new one
            </button>
        </form>

        <p class="auth-bottom">
            <a href="{{ route('password.request') }}">Use a different email</a>
            &nbsp;·&nbsp;
            <a href="{{ route('login') }}">Back to login</a>
        </p>

    </div>
</div>

<script>
    function togglePassword(inputId, button) {
        const input = document.getElementById(inputId);

        if (input.type === 'password') {
            input.type = 'text';
            button.textContent = '🙈';
            button.setAttribute('aria-label', 'Hide password');
        } else {
            input.type = 'password';
            button.textContent = '👁';
            button.setAttribute('aria-label', 'Show password');
        }
    }
</script>

@endsection
