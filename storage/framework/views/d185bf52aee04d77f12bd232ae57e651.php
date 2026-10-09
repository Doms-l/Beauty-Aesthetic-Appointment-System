

<?php $__env->startSection('title', 'Client Dashboard | M. Cares'); ?>

<?php $__env->startSection('content'); ?>
<?php echo $__env->make('partials.dashboard-slider', [
    'eyebrow' => 'CLIENT DASHBOARD',
    'title'   => 'Welcome, ' . auth()->user()->first_name . '.',
    'text'    => 'Manage your profile and beauty appointments here.',
], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<section class="section compact">
    <div class="container">
        <div class="dashboard-actions"><a class="primary-button" href="<?php echo e(route('client.appointments.create')); ?>">+ Book appointment</a><a class="secondary-button" href="<?php echo e(route('client.profile')); ?>">Edit profile</a></div>
        <div class="dashboard-grid">
            <div class="panel spotlight">
                <span class="eyebrow">NEXT APPOINTMENT</span>
                <?php if($upcoming): ?>
                    <h2><?php echo e($upcoming->service->name); ?></h2>
                    <p><?php echo e($upcoming->appointment_date->format('F d, Y')); ?> · <?php echo e(\Carbon\Carbon::parse($upcoming->appointment_time)->format('h:i A')); ?></p>
                    <span class="status <?php echo e($upcoming->status); ?>"><?php echo e(ucfirst($upcoming->status)); ?></span>
                <?php else: ?>
                    <h2>No upcoming appointment</h2>
                    <p>Book your next beauty service when you're ready.</p>
                <?php endif; ?>
            </div>
            <div class="panel"><span class="eyebrow">ACCOUNT</span><h2><?php echo e(auth()->user()->full_name); ?></h2><p><?php echo e(auth()->user()->email); ?></p><p><?php echo e(auth()->user()->phone); ?></p><a href="<?php echo e(route('client.profile')); ?>">View profile →</a></div>
        </div>
    </div>
</section>
<section class="section soft-section compact">
    <div class="container"><div class="section-heading inline-heading"><div><span class="eyebrow">APPOINTMENT HISTORY</span><h2>Your appointments</h2></div><a href="<?php echo e(route('client.appointments')); ?>">View all →</a></div>
        <div class="table-wrap"><table><thead><tr><th>Date</th><th>Service</th><th>Status</th></tr></thead><tbody>
        <?php $__empty_1 = true; $__currentLoopData = $appointments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $appointment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><tr><td><?php echo e($appointment->appointment_date->format('M d, Y')); ?></td><td><?php echo e($appointment->service->name); ?></td><td><span class="status <?php echo e($appointment->status); ?>"><?php echo e(ucfirst($appointment->status)); ?></span></td></tr><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><tr><td colspan="3">No appointment records yet.</td></tr><?php endif; ?>
        </tbody></table></div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Regis D\Desktop\Beauty-Aesthetic-Appointment-System\resources\views/client/dashboard.blade.php ENDPATH**/ ?>