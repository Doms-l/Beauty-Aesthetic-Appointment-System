 
 
<?php $__env->startSection('title', 'Register | M. Cares'); ?> 
 
<?php $__env->startSection('content'); ?> 
<div class="auth-page"> 
    <div class="auth-card wide"> 
        <div class="auth-logo">
            <img src="<?php echo e(asset('images/logo.png')); ?>" alt="M. Cares logo">
        </div> 

        <span class="eyebrow">NEW CLIENT</span> 

        <h1>Create your account</h1> 

        <p class="muted">
            Your account keeps your contact details and appointment history organized.
        </p> 
 
        <form method="POST" action="<?php echo e(route('register.store')); ?>" class="form-stack"> 
            <?php echo csrf_field(); ?> 

            <div class="form-grid two"> 

                <label>
                    First name
                    <input
                        type="text"
                        name="first_name"
                        value="<?php echo e(old('first_name')); ?>"
                        required
                    >
                </label> 

                <label>
                    Last name
                    <input
                        type="text"
                        name="last_name"
                        value="<?php echo e(old('last_name')); ?>"
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
                        value="<?php echo e(old('email')); ?>"
                        required
                    >
                </label> 

                <label>
                    Phone number
                    <input
                        type="tel"
                        name="phone"
                        value="<?php echo e(old('phone')); ?>"
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
                        value="<?php echo e(old('date_of_birth')); ?>"
                        max="<?php echo e(now()->subYears(18)->format('Y-m-d')); ?>"
                        required
                    >
                </label> 

                <label>
                    Address
                    <input
                        type="text"
                        name="address"
                        value="<?php echo e(old('address')); ?>"
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
            <a href="<?php echo e(route('login')); ?>">Login here</a>
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

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Regis D\Desktop\Beauty-Aesthetic-Appointment-System\resources\views/auth/register.blade.php ENDPATH**/ ?>