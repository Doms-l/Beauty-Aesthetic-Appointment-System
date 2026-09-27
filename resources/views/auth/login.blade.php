@extends('layouts.app')

@section('title', 'Login | M. Cares')

@section('content')
<div class="auth-page">
    <div class="auth-card">
        <div class="auth-logo"><img src="{{ asset('images/logo.png') }}" alt="M. Cares logo"></div>
        <span class="eyebrow">WELCOME BACK</span>
        <h1>Login to your account</h1>
        <p class="muted">Manage your appointments and profile from one place.</p>

        <form method="POST" action="{{ route('login.store') }}" class="form-stack">
            @csrf
            <label>Email address<input type="email" name="email" value="{{ old('email') }}" required autofocus></label>
            <label>Password<input type="password" name="password" required></label>
            <label class="check-row"><input type="checkbox" name="remember" value="1"> Remember me</label>
            <button class="primary-button full" type="submit">Login</button>
        </form>

        <p class="auth-bottom">Don't have an account? <a href="{{ route('register') }}">Create one</a></p>
        <div class="demo-box"><strong>Demo admin</strong><br>admin@mcares.test<br>Admin@12345<br><small>Change this password before real deployment.</small></div>
    </div>
</div>
@endsection
