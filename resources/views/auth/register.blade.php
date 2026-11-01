@extends('layouts.app') 
 
@section('title', 'Register | M. Care') 
 
@section('content') 
<div class="auth-page"> 
    <div class="auth-card wide"> 
        <div class="auth-logo">
            <img src="{{ asset('images/logo.png') }}" alt="M. Care logo">
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
                        oninput="this.value = this.value.replace(/\D/g, '').slice(0, 11);"
                        onkeydown="return event.key >= '0' && event.key <= '9' || ['Backspace', 'Delete', 'ArrowLeft', 'ArrowRight', 'Tab'].includes(event.key);"
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

            {{-- Terms & Conditions / Data Privacy consent --}}
            <label class="terms-check">
                <input
                    type="checkbox"
                    name="terms"
                    id="terms"
                    value="1"
                    {{ old('terms') ? 'checked' : '' }}
                    required
                >
                <span>
                    I have read and agree to the
                    <a href="#" id="open-terms">Terms and Conditions and Data Privacy Notice</a>.
                </span>
            </label>

            @error('terms')
                <p class="terms-error">{{ $message }}</p>
            @enderror

            <button class="primary-button full" type="submit" id="register-submit" disabled>
                Create Client Account
            </button> 

        </form> 
 
        <p class="auth-bottom">
            Already registered?
            <a href="{{ route('login') }}">Login here</a>
        </p> 

    </div> 
</div> 


{{-- Terms & Conditions window --}}
<div id="terms-modal" class="terms-modal" hidden>
    <div class="terms-backdrop" data-close-terms></div>
    <div class="terms-dialog" role="dialog" aria-modal="true" aria-labelledby="terms-title">
        <button type="button" class="terms-close" data-close-terms aria-label="Close">✕</button>
        <h2 id="terms-title">Terms and Conditions &amp; Data Privacy Notice</h2>

        <div class="terms-body">
            <h3>1. Data we collect</h3>
            <p>To create your account and handle your appointments, M. Cares Beauty Services collects your name, email address, phone number, date of birth, address (optional), and your appointment history.</p>

            <h3>2. Why we collect it</h3>
            <p>We use your information only to verify your identity, manage your bookings, contact you about your appointments, and improve our service. We do not sell your personal information.</p>

            <h3>3. How we protect it</h3>
            <p>Your password is stored in encrypted (hashed) form. Only authorized clinic staff can view your appointment details, and only when needed to serve you.</p>

            <h3>4. Your rights</h3>
            <p>Under the Data Privacy Act of 2012 (Republic Act No. 10173), you have the right to be informed, to access, correct, and request deletion of your personal data, and to withdraw your consent. Contact the clinic to exercise these rights.</p>

            <h3>5. Appointments and conduct</h3>
            <p>You agree to give accurate information and to attend or cancel your appointments in good time. Repeated cancellations may lead to your account being restricted, as shown in your client level rules.</p>

            <h3>6. Age requirement</h3>
            <p>You must be at least 18 years old to register.</p>

            <h3>7. Consent</h3>
            <p>By ticking the box and creating an account, you confirm that you have read this notice and consent to the collection and use of your personal data as described.</p>
        </div>

        <div class="terms-actions">
            <button type="button" class="primary-button" id="agree-terms">I agree</button>
            <button type="button" class="terms-secondary" data-close-terms>Close</button>
        </div>
    </div>
</div>

<style>
    .form-stack label.terms-check { display:flex; flex-direction:row; align-items:flex-start; gap:12px; font-weight:500; line-height:1.45; }
    .form-stack .terms-check input[type="checkbox"] {
        width:20px; height:20px; min-height:0; padding:0; margin:2px 0 0; flex:0 0 20px;
        accent-color: var(--mauve, #8b5a6e);
    }
    .form-stack .terms-check a { font-weight:700; text-decoration:underline; }
    .terms-error { color:#d64b6b; margin:4px 0 0; }
    #register-submit:disabled { opacity:.5; cursor:not-allowed; }

    .terms-modal { position:fixed; inset:0; z-index:300; display:flex; align-items:center; justify-content:center; padding:16px; }
    .terms-modal[hidden] { display:none; }
    .terms-backdrop { position:absolute; inset:0; background:rgba(0,0,0,.55); }
    .terms-dialog {
        position:relative; width:100%; max-width:620px; max-height:88vh; display:flex; flex-direction:column;
        background:var(--surface, #fff); color:var(--heading, #4a2f3a);
        border:1px solid var(--line, #eadbe2); border-radius:20px; padding:24px;
        box-shadow:var(--shadow, 0 20px 60px rgba(0,0,0,.3));
    }
    .terms-dialog h2 { margin:0 40px 12px 0; font-size:1.3rem; }
    .terms-body { overflow-y:auto; padding-right:8px; }
    .terms-body h3 { margin:14px 0 4px; font-size:1rem; }
    .terms-body p { margin:0; line-height:1.55; }
    .terms-close { position:absolute; top:14px; right:14px; border:0; background:transparent; font-size:1.2rem; cursor:pointer; color:inherit; }
    .terms-actions { display:flex; gap:10px; margin-top:16px; flex-wrap:wrap; }
    .terms-secondary { padding:12px 20px; border-radius:999px; border:1px solid var(--line, #eadbe2); background:transparent; color:inherit; font-weight:700; cursor:pointer; }
</style>

<script>
    (function () {
        var box    = document.getElementById('terms');
        var submit = document.getElementById('register-submit');
        var modal  = document.getElementById('terms-modal');

        function sync() { submit.disabled = !box.checked; }
        box.addEventListener('change', sync);
        sync();

        function openTerms()  { modal.hidden = false; }
        function closeTerms() { modal.hidden = true; }

        document.getElementById('open-terms').addEventListener('click', function (e) {
            e.preventDefault();
            openTerms();
        });
        modal.querySelectorAll('[data-close-terms]').forEach(function (el) {
            el.addEventListener('click', closeTerms);
        });
        document.getElementById('agree-terms').addEventListener('click', function () {
            box.checked = true;
            sync();
            closeTerms();
        });
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') closeTerms();
        });
    })();
</script>

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