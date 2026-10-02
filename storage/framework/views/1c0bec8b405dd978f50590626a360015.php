

<?php $__env->startSection('title', 'Services | M. Cares Beauty Services'); ?>

<?php $__env->startSection('content'); ?>


<section class="page-hero">
    <div class="container">

        <span class="eyebrow">
            M. CARES BEAUTY SERVICES
        </span>

        <h1>
            Our Beauty Services
        </h1>

        <p>
            Explore the beauty treatments and services available
            at M. Cares Beauty Services.
        </p>

    </div>
</section>



<section class="section">

    <div class="container">

        <?php $__empty_1 = true; $__currentLoopData = $groupedServices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category => $categoryServices): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

            <div class="services-category">

                <div class="section-heading">

                    <span class="eyebrow">
                        M. CARES
                    </span>

                    <h2>
                        <?php echo e($category); ?>

                    </h2>

                </div>


                <div class="card-grid service-list-grid">

                    <?php $__currentLoopData = $categoryServices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                        <article class="service-card service-page-card">

                            <h3>
                                <?php echo e($service->name); ?>

                            </h3>

                            <?php if($service->description): ?>
                                <p>
                                    <?php echo e($service->description); ?>

                                </p>
                            <?php else: ?>
                                <p>
                                    Available at M. Cares Beauty Services.
                                </p>
                            <?php endif; ?>

                            <div class="service-meta">

                                <span>
                                    Price
                                </span>

                                <strong>
                                    <?php echo e($service->display_price); ?>

                                </strong>

                            </div>


                            <?php if(auth()->guard()->check()): ?>

                                <?php if(auth()->user()->isClient()): ?>

                                    <div class="service-card-action">

                                        <a
                                            href="<?php echo e(route('client.appointments.create', ['service' => $service->id])); ?>"
                                            class="primary-button"
                                        >
                                            Book Now
                                        </a>

                                    </div>

                                <?php endif; ?>

                            <?php else: ?>

                                <div class="service-card-action">

                                    <a
                                        href="<?php echo e(route('login')); ?>"
                                        class="primary-button"
                                    >
                                        Login to Book
                                    </a>

                                </div>

                            <?php endif; ?>

                        </article>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                </div>

            </div>

        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

            <div class="empty-state">

                <h3>
                    No services available
                </h3>

                <p>
                    Our services are currently being updated.
                    Please check again later.
                </p>

            </div>

        <?php endif; ?>

    </div>

</section>



<section class="section compact soft-section">

    <div class="container">

        <div class="panel spotlight">

            <span class="eyebrow">
                READY TO BOOK?
            </span>

            <h2>
                Choose your treatment and request an appointment.
            </h2>

            <p>
                Browse our available services and select the treatment
                that fits your beauty and care needs.
            </p>

            <?php if(auth()->guard()->check()): ?>

                <?php if(auth()->user()->isClient()): ?>

                    <a
                        href="<?php echo e(route('client.appointments.create')); ?>"
                        class="primary-button"
                    >
                        Book an Appointment
                    </a>

                <?php endif; ?>

            <?php else: ?>

                <a
                    href="<?php echo e(route('register')); ?>"
                    class="primary-button"
                >
                    Create an Account
                </a>

            <?php endif; ?>

        </div>

    </div>

</section>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Regis D\Desktop\Beauty-Aesthetic-Appointment-System\resources\views/services/index.blade.php ENDPATH**/ ?>