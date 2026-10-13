<?php $__env->startSection('title', 'Enter Code | M. Cares'); ?>

<?php $__env->startSection('content'); ?>

<div class="auth-page">
    <div class="auth-card">

        <div class="auth-logo">
            <img src="<?php echo e(asset('images/logo.png')); ?>" alt="M. Cares logo">
        </div>

        <span class="eyebrow">CHECK YOUR EMAIL</span>

        <h1>Enter your code</h1>

        <p class="muted">
            We sent a 6-digit code to <strong><?php echo e($maskedEmail); ?></strong>.
            It expires in <?php echo e(\App\Support\VerificationCodes::TTL_MINUTES); ?> minutes.
        </p>

        <form method="POST" action="<?php echo e(route('password.update')); ?>" class="form-stack">
            <?php echo csrf_field(); ?>

            <label>
                6-digit code

                <input
                    type="text"
                    name="code"
                    value="<?php echo e(old('code')); ?>"
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

        <form method="POST" action="<?php echo e(route('password.resend')); ?>" class="auth-bottom">
            <?php echo csrf_field(); ?>
            Didn't get the code?
            <button
                type="submit"
                style="background:none;border:0;padding:0;font:inherit;color:inherit;font-weight:700;text-decoration:underline;cursor:pointer;"
            >
                Send a new one
            </button>
        </form>

        <p class="auth-bottom">
            <a href="<?php echo e(route('password.request')); ?>">Use a different email</a>
            &nbsp;·&nbsp;
            <a href="<?php echo e(route('login')); ?>">Back to login</a>
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

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Regis D\Desktop\Beauty-Aesthetic-Appointment-System\resources\views/auth/reset-password.blade.php ENDPATH**/ ?>