<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'M. Cares Beauty Services'); ?></title>
    <link rel="icon" type="image/png" href="<?php echo e(asset('images/round.png')); ?>">
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.jsx']); ?>
</head>
<body>
<header class="site-header">
    <div class="container nav-wrap">
        <a class="brand" href="<?php echo e(route('home')); ?>">
            <img src="<?php echo e(asset('images/logo.png')); ?>" alt="M. Cares Beauty Services logo">
            <span>M. CARES<br><small>BEAUTY SERVICES</small></span>
        </a>

        <nav class="main-nav">
            <a href="<?php echo e(route('home')); ?>">Home</a>
            <a href="<?php echo e(route('services.index')); ?>">Services</a>
            <?php if(auth()->guard()->check()): ?>
                <?php if(auth()->user()->isClient()): ?>
                    <a href="<?php echo e(route('client.appointments')); ?>">Appointments</a>
                    <a href="<?php echo e(route('client.profile')); ?>">Profile</a>
                    <a class="nav-cta" href="<?php echo e(route('client.appointments.create')); ?>">Book Now</a>
                <?php elseif(auth()->user()->isAdmin()): ?>
                    <a href="<?php echo e(route('admin.dashboard')); ?>">Admin Dashboard</a>
                <?php elseif(auth()->user()->isStaff()): ?>
                    <a href="<?php echo e(route('staff.dashboard')); ?>">Staff Dashboard</a>
                <?php endif; ?>
                <form method="POST" action="<?php echo e(route('logout')); ?>" class="inline-form">
                    <?php echo csrf_field(); ?>
                    <button class="link-button" type="submit">Logout</button>
                </form>
            <?php else: ?>
                <a href="<?php echo e(route('login')); ?>">Login</a>
                <a class="nav-cta" href="<?php echo e(route('register')); ?>">Register</a>
            <?php endif; ?>
        </nav>
    </div>
</header>

<?php if(session('success')): ?>
    <div class="container flash success"><?php echo e(session('success')); ?></div>
<?php endif; ?>

<?php if($errors->any()): ?>
    <div class="container flash error">
        <strong>Please check the form.</strong>
        <ul>
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><?php echo e($error); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
<?php endif; ?>

<main>
    <?php echo $__env->yieldContent('content'); ?>
</main>

<footer class="site-footer">
    <div class="container footer-grid">
        <div>
            <h3>M. CARES</h3>
            <p>Beauty, care, and confidence in one place.</p>
        </div>
        <div>
            <p>Web-Based Aesthetic Clinic Appointment and Management System</p>
            <p>© <?php echo e(date('Y')); ?> M. Cares Beauty Services</p>
        </div>
    </div>
</footer>
</body>
</html>
<?php /**PATH C:\Users\Jennely\Desktop\Beauty-Aesthetic-Appointment-System\resources\views/layouts/app.blade.php ENDPATH**/ ?>