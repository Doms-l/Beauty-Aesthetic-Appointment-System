
@extends('layouts.app')

@section('title', 'Login | M. Cares')

@section('content')
<<<<<<< HEAD

<div class="auth-page">
    <div class="auth-card">

=======

<div class="auth-page">

    <div class="auth-card">

>>>>>>> main
        <div class="auth-logo">
            <img
                src="{{ asset('images/logo.png') }}"
                alt="M. Cares logo"
            >
        </div>

<<<<<<< HEAD
        <span class="eyebrow">WELCOME BACK</span>

        <h1>Login to your account</h1>
=======
        <span class="eyebrow">
            WELCOME BACK
        </span>

        <h1>
            Login to your account
        </h1>
>>>>>>> main

        <p class="muted">
            Manage your appointments and profile from one place.
        </p>

        <form
            method="POST"
            action="{{ route('login.store') }}"
            class="form-stack"
            id="loginForm"
        >
<<<<<<< HEAD
            @csrf

=======

            @csrf

            {{-- Email --}}
>>>>>>> main
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

<<<<<<< HEAD
=======
            {{-- Password with eye toggle --}}
>>>>>>> main
            <label>
                Password

                <div class="password-input-wrapper">
<<<<<<< HEAD
=======

>>>>>>> main
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
<<<<<<< HEAD
                        id="loginPasswordToggle"
                        onclick="toggleLoginPassword()"
                        aria-label="Show password"
                        title="Show password"
                    >
                        👁
                    </button>
                </div>
            </label>

            <label class="check-row">
=======
                        id="passwordToggle"
                        aria-label="Show password"
                        aria-controls="loginPassword"
                    >
                        👁
                    </button>

                </div>
            </label>

            {{-- Remember Me --}}
            <label class="check-row">

>>>>>>> main
                <input
                    type="checkbox"
                    name="remember"
                    value="1"
                    id="rememberMe"
                >
<<<<<<< HEAD
                Remember me
=======

                Remember me

>>>>>>> main
            </label>

            <button
                class="primary-button full"
                type="submit"
            >
                Login
            </button>
<<<<<<< HEAD
=======

>>>>>>> main
        </form>

        <p class="auth-bottom">
            Don't have an account?
<<<<<<< HEAD
            <a href="{{ route('register') }}">Create one</a>
=======
            <a href="{{ route('register') }}">
                Create one
            </a>
>>>>>>> main
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
<<<<<<< HEAD
function toggleLoginPassword() {
    const password = document.getElementById('loginPassword');
    const button = document.getElementById('loginPasswordToggle');

    if (password.type === 'password') {
        password.type = 'text';
        button.textContent = '🙈';
        button.setAttribute('aria-label', 'Hide password');
        button.setAttribute('title', 'Hide password');
    } else {
        password.type = 'password';
        button.textContent = '👁';
        button.setAttribute('aria-label', 'Show password');
        button.setAttribute('title', 'Show password');
    }
}

document.addEventListener('DOMContentLoaded', function () {
    const emailInput = document.getElementById('loginEmail');
    const rememberCheckbox = document.getElementById('rememberMe');
    const loginForm = document.getElementById('loginForm');

=======
document.addEventListener('DOMContentLoaded', function () {

    const emailInput = document.getElementById('loginEmail');
    const passwordInput = document.getElementById('loginPassword');
    const passwordToggle = document.getElementById('passwordToggle');
    const rememberCheckbox = document.getElementById('rememberMe');
    const loginForm = document.getElementById('loginForm');

    /*
    |--------------------------------------------------------------------------
    | Show / Hide Password
    |--------------------------------------------------------------------------
    */

    passwordToggle.addEventListener('click', function () {

        if (passwordInput.type === 'password') {

            passwordInput.type = 'text';

            passwordToggle.textContent = '🙈';

            passwordToggle.setAttribute(
                'aria-label',
                'Hide password'
            );

        } else {

            passwordInput.type = 'password';

            passwordToggle.textContent = '👁';

            passwordToggle.setAttribute(
                'aria-label',
                'Show password'
            );

        }

    });

    /*
    |--------------------------------------------------------------------------
    | Restore Remember Me Settings
    |--------------------------------------------------------------------------
    */

>>>>>>> main
    const savedEmail = localStorage.getItem(
        'mcares_remembered_email'
    );

    const savedRemember = localStorage.getItem(
        'mcares_remember_me'
    );

<<<<<<< HEAD
=======
    // Restore the saved email
>>>>>>> main
    if (savedEmail && !emailInput.value) {
        emailInput.value = savedEmail;
    }

<<<<<<< HEAD
=======
    // Restore the checkbox
>>>>>>> main
    if (savedRemember === 'true') {
        rememberCheckbox.checked = true;
    }

<<<<<<< HEAD
    loginForm.addEventListener('submit', function () {
        if (rememberCheckbox.checked) {
=======
    /*
    |--------------------------------------------------------------------------
    | Save Email and Remember Me Choice
    |--------------------------------------------------------------------------
    */

    loginForm.addEventListener('submit', function () {

        if (rememberCheckbox.checked) {

>>>>>>> main
            localStorage.setItem(
                'mcares_remembered_email',
                emailInput.value
            );

            localStorage.setItem(
                'mcares_remember_me',
                'true'
            );
<<<<<<< HEAD
        } else {
=======

        } else {

>>>>>>> main
            localStorage.removeItem(
                'mcares_remembered_email'
            );

            localStorage.removeItem(
                'mcares_remember_me'
            );
<<<<<<< HEAD
        }
    });
});
</script>

@endsection
=======

        }

    });

});
</script>

@endsection
>>>>>>> main
