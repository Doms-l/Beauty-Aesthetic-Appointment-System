@extends('layouts.app')

@section('title', 'Forgot Password | M. Care')

@section('content')

<div class="auth-page">
    <div class="auth-card">

        <div class="auth-logo">
            <img src="{{ asset('images/logo.png') }}" alt="M. Care logo">
        </div>

        <span class="eyebrow">FORGOT PASSWORD</span>

        <h1>Reset your password</h1>

        <p class="muted">
            Enter the email address of your account. We'll email you a
            6-digit code to choose a new password.
        </p>

        <form method="POST" action="{{ route('password.email') }}" class="form-stack">
            @csrf

            <label>
                Email address

                <input
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    autocomplete="email"
                >
            </label>

            <button class="primary-button full" type="submit">
                Send Code
            </button>
        </form>

        <p class="auth-bottom">
            Remembered it?
            <a href="{{ route('login') }}">Back to login</a>
        </p>

    </div>
</div>

@endsection
