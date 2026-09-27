<?php $__env->startSection('title', 'Login | M. Cares'); ?>

<?php $__env->startSection('content'); ?>
<div class="auth-page">
    <div class="auth-card">
        <div class="auth-logo"><img src="<?php echo e(asset('images/logo.png')); ?>" alt="M. Cares logo"></div>
        <span class="eyebrow">WELCOME BACK</span>
        <h1>Login to your account</h1>
        <p class="muted">Manage your appointments and profile from one place.</p>

        <form method="POST" action="<?php echo e(route('login.store')); ?>" class="form-stack">
            <?php echo csrf_field(); ?>
            <label>Email address<input type="email" name="email" value="<?php echo e(old('email')); ?>" required autofocus></label>
            <label>Password<input type="password" name="password" required></label>
            <label class="check-row"><input type="checkbox" name="remember" value="1"> Remember me</label>
            <button class="primary-button full" type="submit">Login</button>
        </form>

        <p class="auth-bottom">Don't have an account? <a href="<?php echo e(route('register')); ?>">Create one</a></p>
        <div class="demo-box"><strong>Demo admin</strong><br>admin@mcares.test<br>Admin@12345<br><small>Change this password before real deployment.</small></div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Regis D\Desktop\Beauty-Aesthetic-Appointment-System\resources\views/auth/login.blade.php ENDPATH**/ ?>