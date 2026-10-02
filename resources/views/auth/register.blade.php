@extends('layouts.app') 
 
@section('title', 'Register | M. Cares') 
 
@section('content') 
<div class="auth-page"> 
    <div class="auth-card wide"> 
        <div class="auth-logo">
            <img src="{{ asset('images/logo.png') }}" alt="M. Cares logo">
        </div> 

        <span class="eyebrow">NEW CLIENT</span> 

        <h1>Create your account</h1> 

        <p class="muted">
            Your account keeps your contact details and appointment history organized.
        </p> 
 
        <form method="POST" action="{{ route('register.store') }}" class="form-stack"> 
            @csrf 

            <div class="form-grid two"> 

                <label>
                    First name
                    <input
                        type="text"
                        name="first_name"
                        value="{{ old('first_name') }}"
                        required
                    >
                </label> 

                <label>
                    Last name
                    <input
                        type="text"
                        name="last_name"
                        value="{{ old('last_name') }}"
                        required
                    >
                </label> 

            </div> 

            <div class="form-grid two"> 

                <label>
                    Email address
                    <input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                    >
                </label> 

                <label>
                    Phone number
                    <input
                        type="tel"
                        name="phone"
                        value="{{ old('phone') }}"
                        required
                        maxlength="11"
                        minlength="11"
                        inputmode="numeric"
                        pattern="[0-9]{11}"
                        placeholder="09XXXXXXXXX"
                        oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 11);"
                    >
                </label> 

            </div> 

            <div class="form-grid two"> 

                <label>
                    Date of birth
                    <input
                        type="date"
                        name="date_of_birth"
                        value="{{ old('date_of_birth') }}"
                        max="{{ now()->subYears(18)->format('Y-m-d') }}"
                        required
                    >
                </label> 

                <label>
                    Address
                    <input
                        type="text"
                        name="address"
                        value="{{ old('address') }}"
                    >
                </label> 

            </div> 

            <div class="form-grid two"> 

                <label>
                    Password

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
                    Confirm password

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

            </div> 

            <p class="form-help">
                Password must contain at least 8 characters, including an uppercase letter,
                lowercase letter, number, and special character.
                Public registration always creates a client account.
            </p> 

            <button class="primary-button full" type="submit">
                Create Client Account
            </button> 

        </form> 
 
        <p class="auth-bottom">
            Already registered?
            <a href="{{ route('login') }}">Login here</a>
        </p> 

    </div> 
</div> 


<script>
    function togglePassword(inputId, button) {

        const input = document.getElementById(inputId);

        if (input.type === 'password') {

            input.type = 'text';

            button.textContent = '🙈';

            button.setAttribute(
                'aria-label',
                'Hide password'
            );

        } else {

            input.type = 'password';

            button.textContent = '👁';

            button.setAttribute(
                'aria-label',
                'Show password'
            );
        }
    }
</script>

@endsection