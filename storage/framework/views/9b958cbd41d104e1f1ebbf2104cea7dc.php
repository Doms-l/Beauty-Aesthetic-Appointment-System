<?php $__env->startSection('title', 'Forgot Password | M. Cares'); ?>

<?php $__env->startSection('content'); ?>

<div class="auth-page">
    <div class="auth-card">

        <div class="auth-logo">
            <img src="<?php echo e(asset('images/logo.png')); ?>" alt="M. Cares logo">
        </div>

        <span class="eyebrow">FORGOT PASSWORD</span>

        <h1>Reset your password</h1>

        <p class="muted">
            Enter the email address of your account. We'll email you a
            6-digit code to choose a new password.
        </p>

        <form method="POST" action="<?php echo e(route('password.email')); ?>" class="form-stack">
            <?php echo csrf_field(); ?>

            <label>
                Email address

                <input
                    type="email"
                    name="email"
                    value="<?php echo e(old('email')); ?>"
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
            <a href="<?php echo e(route('login')); ?>">Back to login</a>
        </p>

    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Regis D\Desktop\Beauty-Aesthetic-Appointment-System\resources\views/auth/forgot-password.blade.php ENDPATH**/ ?>