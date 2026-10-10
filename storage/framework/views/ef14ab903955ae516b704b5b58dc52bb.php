 
 
<?php $__env->startSection('title', 'Admin Dashboard | M. Cares'); ?> 
 
<?php $__env->startSection('content'); ?> 

<?php echo $__env->make('partials.dashboard-slider', [
    'eyebrow' => 'ADMINISTRATION',
    'title'   => 'Clinic overview',
    'text'    => 'Monitor appointments, clients, services, and daily clinic activity.',
    'admin'   => true,
], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>


<section class="section compact">

    <div class="container">

        
        <div class="stat-grid">

            <div class="stat-card">
                <span>Today's appointments</span>
                <strong><?php echo e($today); ?></strong>
            </div>

            <div class="stat-card">
                <span>Pending requests</span>
                <strong><?php echo e($pending); ?></strong>
            </div>

            <div class="stat-card">
                <span>Registered clients</span>
                <strong><?php echo e($clients); ?></strong>
            </div>

            <div class="stat-card">
                <span>Available services</span>
                <strong><?php echo e($services); ?></strong>
            </div>

        </div>


        
        <div class="admin-links">

            <a href="<?php echo e(route('admin.appointments')); ?>">
                Appointments →
            </a>

            <a href="<?php echo e(route('admin.services')); ?>">
                Services →
            </a>

            <a href="<?php echo e(route('admin.staff')); ?>">
                Staff →
            </a>

            <a href="<?php echo e(route('admin.promo')); ?>">
                📣 Promo →
            </a>

            <a href="#" class="raffle-open-btn">
                🎡 Raffle Wheel →
            </a>

        </div>


        

        <div class="panel popular-services-panel">

            <div class="section-heading">

                <div>

                    <span class="eyebrow">
                        ANALYTICS
                    </span>

                    <h2>
                        Most Booked Services
                    </h2>

                    <p>
                        Services with the highest number of
                        non-cancelled appointment requests.
                    </p>

                </div>

            </div>


            <?php if($popularServices->count()): ?>

                <?php
                    $maxBookings = $popularServices->max('total_bookings');
                ?>


                <div class="popular-services-chart">

                    <?php $__currentLoopData = $popularServices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $popularService): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                        <?php
                            $percentage = $maxBookings > 0
                                ? ($popularService->total_bookings / $maxBookings) * 100
                                : 0;
                        ?>


                        <div class="chart-row">

                            <div class="chart-label">

                                <span>
                                    <?php echo e($popularService->service->name); ?>

                                </span>

                                <strong>
                                    <?php echo e($popularService->total_bookings); ?>

                                    <?php echo e($popularService->total_bookings == 1 ? 'booking' : 'bookings'); ?>

                                </strong>

                            </div>


                            <div class="chart-bar-background">

                                <div
                                    class="chart-bar"
                                    style="width: <?php echo e($percentage); ?>%;"
                                ></div>

                            </div>

                        </div>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                </div>

            <?php else: ?>

                <div class="chart-empty">

                    <p>
                        No appointment data available yet.
                    </p>

                </div>

            <?php endif; ?>

        </div>


        
        <?php echo $__env->make('partials.income-analytics', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>


        

        <div class="panel">

            <div class="section-heading inline-heading">

                <div>

                    <span class="eyebrow">
                        UPCOMING
                    </span>

                    <h2>
                        Appointment schedule
                    </h2>

                </div>

                <a href="<?php echo e(route('admin.appointments')); ?>">
                    Manage →
                </a>

            </div>


            <div class="table-wrap">

                <table>

                    <thead>

                        <tr>
                            <th>Date</th>
                            <th>Client</th>
                            <th>Service</th>
                            <th>Status</th>
                        </tr>

                    </thead>


                    <tbody>

                        <?php $__empty_1 = true; $__currentLoopData = $appointments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $appointment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                            <tr>

                                <td>
                                    <?php echo e($appointment->appointment_date->format('M d, Y')); ?>


                                    <?php echo e(\Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A')); ?>

                                </td>

                                <td>
                                    <?php echo e($appointment->user->full_name); ?>

                                </td>

                                <td>
                                    <?php echo e($appointment->service->name); ?>

                                </td>

                                <td>

                                    <span class="status <?php echo e($appointment->status); ?>">
                                        <?php echo e(ucfirst($appointment->status)); ?>

                                    </span>

                                </td>

                            </tr>

                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

                            <tr>

                                <td colspan="4">
                                    No upcoming appointments.
                                </td>

                            </tr>

                        <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</section> 

<?php echo $__env->make('partials.raffle-wheel', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Regis D\Desktop\Beauty-Aesthetic-Appointment-System\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>