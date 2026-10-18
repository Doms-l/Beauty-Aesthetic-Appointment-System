<?php $__env->startSection('title', 'My Appointments | M. Cares'); ?>

<?php $__env->startSection('content'); ?>

<section class="page-hero">
    <div class="container">
        <span class="eyebrow">STAFF · APPOINTMENTS</span>
        <h1>My appointments</h1>
        <p>View your schedule, confirm requests, update dates and mark appointments as completed.</p>
    </div>
</section>

<section class="section compact">
    <div class="container">

        <?php if($errors->any()): ?>
            <p style="color:#d64b6b;font-weight:600;"><?php echo e($errors->first()); ?></p>
        <?php endif; ?>

        <div class="dashboard-actions" style="margin-bottom:12px;">
            <?php $__currentLoopData = ['today' => 'Today', 'upcoming' => 'Upcoming', 'past' => 'Past', 'all' => 'All']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a class="<?php echo e($view === $key ? 'primary-button' : 'secondary-button'); ?>"
                   href="<?php echo e(route('staff.appointments', array_filter(['view' => $key, 'status' => $status]))); ?>">
                    <?php echo e($label); ?>

                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <form method="GET" action="<?php echo e(route('staff.appointments')); ?>" class="form-stack" style="margin:0 0 16px; max-width:260px;">
            <input type="hidden" name="view" value="<?php echo e($view); ?>">
            <select name="status" onchange="this.form.submit()">
                <option value="">All statuses</option>
                <?php $__currentLoopData = ['pending','confirmed','rescheduled','completed','cancelled']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($s); ?>" <?php if($status === $s): echo 'selected'; endif; ?>><?php echo e(ucfirst($s)); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </form>

        <?php echo $__env->make('staff.partials.appointment-table', ['rows' => $appointments, 'staff' => $staff, 'empty' => 'No appointments found.'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <div class="pagination"><?php echo e($appointments->links()); ?></div>

    </div>
</section>

<?php echo $__env->make('staff.partials.appointment-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Regis D\Desktop\Beauty-Aesthetic-Appointment-System\resources\views/staff/appointments.blade.php ENDPATH**/ ?>