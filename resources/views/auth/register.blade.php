@extends('layouts.app')

@section('title', 'Register | M. Cares')

@section('content')
<div class="auth-page">
    <div class="auth-card wide">
        <div class="auth-logo"><img src="{{ asset('images/logo.png') }}" alt="M. Cares logo"></div>
        <span class="eyebrow">NEW CLIENT</span>
        <h1>Create your account</h1>
        <p class="muted">Your account keeps your contact details and appointment history organized.</p>

        <form method="POST" action="{{ route('register.store') }}" class="form-stack">
            @csrf
            <div class="form-grid two">
                <label>First name<input type="text" name="first_name" value="{{ old('first_name') }}" required></label>
                <label>Last name<input type="text" name="last_name" value="{{ old('last_name') }}" required></label>
            </div>
            <div class="form-grid two">
                <label>Email address<input type="email" name="email" value="{{ old('email') }}" required></label>
                <label>Phone number<input type="text" name="phone" value="{{ old('phone') }}" required></label>
            </div>
            <div class="form-grid two">
                <label>Date of birth<input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}"></label>
                <label>Address<input type="text" name="address" value="{{ old('address') }}"></label>
            </div>
            <div class="form-grid two">
                <label>Password<input type="password" name="password" required minlength="8"></label>
                <label>Confirm password<input type="password" name="password_confirmation" required minlength="8"></label>
            </div>
            <p class="form-help">Password must contain at least 8 characters. Public registration always creates a client account.</p>
            <button class="primary-button full" type="submit">Create Client Account</button>
        </form>

        <p class="auth-bottom">Already registered? <a href="{{ route('login') }}">Login here</a></p>
    </div>
</div>
@endsection
