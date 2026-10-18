

<?php $__env->startSection('title', 'Verify Email | M. Cares'); ?>

<?php $__env->startSection('content'); ?>

<div class="auth-page">
    <div class="auth-card">

        <div class="auth-logo">
            <img src="<?php echo e(asset('images/logo.png')); ?>" alt="M. Cares logo">
        </div>

        <span class="eyebrow">VERIFY YOUR EMAIL</span>

        <h1>Enter your code</h1>

        <p class="muted">
            We sent a 6-digit code to <strong><?php echo e($maskedEmail); ?></strong>.
            Enter it to finish creating your account. It expires in
            <?php echo e(\App\Support\VerificationCodes::TTL_MINUTES); ?> minutes.
        </p>

        <form method="POST" action="<?php echo e(route('register.verify.store')); ?>" class="form-stack">
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

            <button class="primary-button full" type="submit">
                Verify &amp; Create Account
            </button>
        </form>

        <form method="POST" action="<?php echo e(route('register.resend')); ?>" class="auth-bottom">
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
            Wrong email?
            <a href="<?php echo e(route('register')); ?>">Go back</a>
        </p>

    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Regis D\Desktop\Beauty-Aesthetic-Appointment-System\resources\views/auth/verify-email.blade.php ENDPATH**/ ?>