<?php $__env->startSection('title', 'Services | M. Cares'); ?>

<?php $__env->startSection('content'); ?>
<section class="page-hero">
    <div class="container"><span class="eyebrow">M. CARES SERVICES</span><h1>Beauty and aesthetic services</h1><p>Browse the services currently available at the clinic.</p></div>
</section>
<section class="section">
    <div class="container card-grid three">
        <?php $__empty_1 = true; $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <article class="service-card large">
                <div class="service-icon">✿</div>
                <h2><?php echo e($service->name); ?></h2>
                <p><?php echo e($service->description); ?></p>
                <div class="service-meta"><strong>₱<?php echo e(number_format($service->price, 2)); ?></strong><span><?php echo e($service->duration_minutes); ?> min</span></div>
                <?php if(auth()->guard()->check()): ?>
                    <?php if(auth()->user()->isClient()): ?>
                        <a class="secondary-button full" href="<?php echo e(route('client.appointments.create')); ?>">Book this service</a>
                    <?php endif; ?>
                <?php else: ?>
                    <a class="secondary-button full" href="<?php echo e(route('register')); ?>">Register to book</a>
                <?php endif; ?>
            </article>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="empty-state">No services are currently available.</div>
        <?php endif; ?>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Regis D\Desktop\Beauty-Aesthetic-Appointment-System\resources\views/services/index.blade.php ENDPATH**/ ?>