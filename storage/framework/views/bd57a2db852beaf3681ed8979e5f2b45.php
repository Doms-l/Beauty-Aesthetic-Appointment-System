<?php $__env->startSection('title', 'M. Cares Beauty Services'); ?>

<?php $__env->startSection('content'); ?>
<section class="hero-section">
    <div class="container hero-grid">
        <div class="hero-copy">
            <span class="eyebrow">BEAUTY · CARE · CONFIDENCE</span>
            <h1>Enhance your beauty.<br><em>Feel your best.</em></h1>
            <p>
                Discover personalized aesthetic and beauty services with an easy online appointment experience at M. Cares Beauty Services.
            </p>
            <div class="hero-actions">
                <a class="primary-button" href="<?php echo e(auth()->check() ? (auth()->user()->isClient() ? route('client.appointments.create') : route('home')) : route('register')); ?>">Book an Appointment</a>
                <a class="secondary-button" href="<?php echo e(route('services.index')); ?>">Explore Services</a>
            </div>
            <div class="hero-note"><span>✦</span> Professional service · Simple booking · Personalized care</div>
        </div>
        <div class="hero-art">
            <div class="logo-orbit orbit-one"></div>
            <div class="logo-orbit orbit-two"></div>
            <img src="<?php echo e(asset('images/logo.png')); ?>" alt="M. Cares logo">
        </div>
    </div>
</section>

<section class="section soft-section">
    <div class="container">
        <div class="section-heading">
            <span class="eyebrow">OUR SERVICES</span>
            <h2>Beauty services made for you.</h2>
            <p>Choose a service, select a preferred schedule, and send your appointment request online.</p>
        </div>
        <div class="card-grid three">
            <?php $__empty_1 = true; $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <article class="service-card">
                    <div class="service-icon">✿</div>
                    <h3><?php echo e($service->name); ?></h3>
                    <p><?php echo e($service->description); ?></p>
                    <div class="service-meta">
                        <strong>₱<?php echo e(number_format($service->price, 2)); ?></strong>
                        <span><?php echo e($service->duration_minutes); ?> min</span>
                    </div>
                </article>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="empty-state">Services will appear here after the administrator adds them.</div>
            <?php endif; ?>
        </div>
        <div class="center-button"><a class="secondary-button" href="<?php echo e(route('services.index')); ?>">View all services</a></div>
    </div>
</section>

<section class="section">
    <div class="container feature-grid">
        <div>
            <span class="eyebrow">WHY M. CARES?</span>
            <h2>A simpler way to manage your beauty appointments.</h2>
        </div>
        <div class="feature-list">
            <div><span>01</span><div><h3>Easy registration</h3><p>Create one client account and keep your information ready for future appointments.</p></div></div>
            <div><span>02</span><div><h3>Online appointment requests</h3><p>Choose a service, preferred date, and time from the client dashboard.</p></div></div>
            <div><span>03</span><div><h3>Organized clinic management</h3><p>Staff and administrators can review schedules, clients, services, and appointment status.</p></div></div>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Regis D\Desktop\Beauty-Aesthetic-Appointment-System\resources\views/home.blade.php ENDPATH**/ ?>