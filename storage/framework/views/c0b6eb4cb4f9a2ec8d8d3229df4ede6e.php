<?php $__env->startSection('title', 'Register | M. Cares'); ?>

<?php $__env->startSection('content'); ?>
<div class="auth-page">
    <div class="auth-card wide">
        <div class="auth-logo"><img src="<?php echo e(asset('images/logo.png')); ?>" alt="M. Cares logo"></div>
        <span class="eyebrow">NEW CLIENT</span>
        <h1>Create your account</h1>
        <p class="muted">Your account keeps your contact details and appointment history organized.</p>

        <form method="POST" action="<?php echo e(route('register.store')); ?>" class="form-stack">
            <?php echo csrf_field(); ?>
            <div class="form-grid two">
                <label>First name<input type="text" name="first_name" value="<?php echo e(old('first_name')); ?>" required></label>
                <label>Last name<input type="text" name="last_name" value="<?php echo e(old('last_name')); ?>" required></label>
            </div>
            <div class="form-grid two">
                <label>Email address<input type="email" name="email" value="<?php echo e(old('email')); ?>" required></label>
                <label>Phone number<input type="text" name="phone" value="<?php echo e(old('phone')); ?>" required></label>
            </div>
            <div class="form-grid two">
                <label>Date of birth<input type="date" name="date_of_birth" value="<?php echo e(old('date_of_birth')); ?>"></label>
                <label>Address<input type="text" name="address" value="<?php echo e(old('address')); ?>"></label>
            </div>
            <div class="form-grid two">
                <label>Password<input type="password" name="password" required minlength="8"></label>
                <label>Confirm password<input type="password" name="password_confirmation" required minlength="8"></label>
            </div>
            <p class="form-help">Password must contain at least 8 characters. Public registration always creates a client account.</p>
            <button class="primary-button full" type="submit">Create Client Account</button>
        </form>

        <p class="auth-bottom">Already registered? <a href="<?php echo e(route('login')); ?>">Login here</a></p>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Regis D\Desktop\Beauty-Aesthetic-Appointment-System\resources\views/auth/register.blade.php ENDPATH**/ ?>