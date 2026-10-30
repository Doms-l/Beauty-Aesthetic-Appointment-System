

<?php $__env->startSection('title', 'Staff Dashboard | M. Care'); ?>

<?php $__env->startSection('content'); ?>

<?php echo $__env->make('partials.dashboard-slider', [
    'eyebrow' => 'STAFF',
    'title'   => 'Welcome, ' . auth()->user()->first_name,
    'text'    => 'Your appointments for today and the days ahead, all in one place.',
    'admin'   => true,
], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<section class="section compact">
    <div class="container">

        <?php if($errors->any()): ?>
            <p style="color:#d64b6b;font-weight:600;"><?php echo e($errors->first()); ?></p>
        <?php endif; ?>

        <?php if (! ($staff)): ?>
            <div class="panel" style="margin-bottom:20px;">
                <strong>Your account has no staff profile yet.</strong>
                <p class="muted" style="margin:6px 0 0;">Ask the admin to finish setting up your staff account. Until then you will not see any appointments.</p>
            </div>
        <?php endif; ?>

        <div class="stat-grid">

            <div class="stat-card">
                <span>Today's appointments</span>
                <strong><?php echo e($stats['today']); ?></strong>
            </div>

            <div class="stat-card">
                <span>Upcoming</span>
                <strong><?php echo e($stats['upcoming']); ?></strong>
            </div>

            <div class="stat-card">
                <span>To confirm</span>
                <strong><?php echo e($stats['pending']); ?></strong>
            </div>

            <div class="stat-card">
                <span>Completed this month</span>
                <strong><?php echo e($stats['completed']); ?></strong>
            </div>

        </div>

        
        <div class="panel" style="margin-top:22px;">
            <div class="section-heading">
                <div>
                    <span class="eyebrow">TODAY</span>
                    <h2>Today's schedule · <?php echo e(now()->format('l, M d')); ?></h2>
                </div>
            </div>
            <?php echo $__env->make('staff.partials.appointment-table', ['rows' => $todayList, 'staff' => $staff, 'empty' => 'No appointments for you today.'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>

        
        <div class="panel" style="margin-top:22px;">
            <div class="section-heading">
                <div>
                    <span class="eyebrow">ACTION NEEDED</span>
                    <h2>Waiting for your confirmation</h2>
                </div>
            </div>
            <?php echo $__env->make('staff.partials.appointment-table', ['rows' => $pendingList, 'staff' => $staff, 'empty' => 'Nothing is waiting for confirmation.'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>

        
        <div class="panel" style="margin-top:22px;">
            <div class="section-heading inline-heading">
                <div>
                    <span class="eyebrow">UPCOMING</span>
                    <h2>Next appointments</h2>
                </div>
                <a href="<?php echo e(route('staff.appointments')); ?>">All my appointments →</a>
            </div>
            <?php echo $__env->make('staff.partials.appointment-table', ['rows' => $upcomingList, 'staff' => $staff, 'empty' => 'No upcoming appointments yet.'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>

        
        <div class="panel" style="margin-top:22px;">
            <div class="section-heading">
                <div>
                    <span class="eyebrow">OPEN REQUESTS</span>
                    <h2>Not assigned yet</h2>
                    <p class="muted" style="margin:4px 0 0;">Clients who chose "No preference". Take one to manage it.</p>
                </div>
            </div>
            <?php echo $__env->make('staff.partials.appointment-table', ['rows' => $claimList, 'staff' => $staff, 'empty' => 'No open requests right now.'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>

    </div>
</section>

<?php echo $__env->make('staff.partials.appointment-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Regis D\Desktop\Beauty-Aesthetic-Appointment-System\resources\views/staff/dashboard.blade.php ENDPATH**/ ?>