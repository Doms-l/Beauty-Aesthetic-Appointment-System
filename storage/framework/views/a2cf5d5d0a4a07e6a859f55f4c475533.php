

<?php $__env->startSection('title', 'Manage Appointments | M. Cares'); ?>

<?php $__env->startSection('content'); ?>

<section class="page-hero">
    <div class="container">
        <span class="eyebrow">ADMIN · APPOINTMENTS</span>
        <h1><?php echo e($showArchived ? 'Archived appointments' : 'Manage appointment requests'); ?></h1>
        <p>
            <?php if($showArchived): ?>
                Archived appointments are hidden from the list and from all analytics.
            <?php else: ?>
                Confirm, reschedule, complete, or cancel client requests. Archive cancelled ones to remove them from the analytics.
            <?php endif; ?>
        </p>
    </div>
</section>

<section class="section compact">

    <div class="container">

        
        <?php if(auth()->user()->isAdmin()): ?>
            <div class="dashboard-actions" style="margin-bottom:16px;">
                <?php if($showArchived): ?>
                    <a class="primary-button" href="<?php echo e(route('admin.appointments')); ?>">← Back to appointments</a>
                <?php else: ?>
                    <a class="primary-button" href="<?php echo e(route('admin.appointments', ['archived' => 1])); ?>">
                        🗄 View archive (<?php echo e($archivedCount); ?>)
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if($errors->any()): ?>
            <p style="color:#d64b6b;"><?php echo e($errors->first()); ?></p>
        <?php endif; ?>

        <div class="table-wrap">

            <table>

                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Client</th>
                        <th>Service</th>
                        <th>Status</th>
                        <th>Staff</th>
                        <th>Save</th>
                        <?php if(auth()->user()->isAdmin()): ?>
                            <th>Archive</th>
                        <?php endif; ?>
                    </tr>
                </thead>

                <tbody>

                <?php $__empty_1 = true; $__currentLoopData = $appointments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $appointment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                    <tr>

                        <td>
                            <?php echo e($appointment->appointment_date->format('M d, Y')); ?><br>
                            <?php echo e(\Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A')); ?>

                        </td>

                        <td>
                            <?php echo e($appointment->user->full_name); ?><br>
                            <small><?php echo e($appointment->user->phone); ?></small>
                        </td>

                        <td><?php echo e($appointment->service->name); ?></td>

                        <td>
                            <form method="POST"
                                  action="<?php echo e(auth()->user()->isAdmin() ? route('admin.appointments.update', $appointment) : route('staff.appointments.update', $appointment)); ?>"
                                  class="table-form">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PATCH'); ?>

                                <select name="status">
                                    <option value="pending" <?php if($appointment->status==='pending'): echo 'selected'; endif; ?>>Pending</option>
                                    <option value="confirmed" <?php if($appointment->status==='confirmed'): echo 'selected'; endif; ?>>Confirmed</option>
                                    <option value="completed" <?php if($appointment->status==='completed'): echo 'selected'; endif; ?>>Completed</option>
                                    <option value="rescheduled" <?php if($appointment->status==='rescheduled'): echo 'selected'; endif; ?>>Rescheduled</option>
                                    <option value="cancelled" <?php if($appointment->status==='cancelled'): echo 'selected'; endif; ?>>Cancelled</option>
                                </select>
                        </td>

                        <td>
                                <select name="staff_id">
                                    <option value=""><?php echo e($appointment->with_owner ? 'Owner (requested)' : 'Unassigned'); ?></option>
                                    <?php $__currentLoopData = $staff; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($member->id); ?>" <?php if($appointment->staff_id===$member->id): echo 'selected'; endif; ?>>
                                            <?php echo e($member->user->full_name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                        </td>

                        <td>
                                <button class="small-button">Save</button>
                            </form>
                        </td>

                        <?php if(auth()->user()->isAdmin()): ?>
                            <td>
                                <?php if($appointment->archived_at): ?>

                                    <form method="POST" action="<?php echo e(route('admin.appointments.restore', $appointment)); ?>" class="inline-form">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('PATCH'); ?>
                                        <button class="small-button">Restore</button>
                                    </form>

                                <?php elseif($appointment->status === 'cancelled'): ?>

                                    <form method="POST" action="<?php echo e(route('admin.appointments.archive', $appointment)); ?>" class="inline-form">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('PATCH'); ?>
                                        <button class="small-button"
                                                onclick="return confirm('Archive this cancelled appointment? It will be removed from the analytics.')">
                                            🗄 Archive
                                        </button>
                                    </form>

                                <?php else: ?>
                                    —
                                <?php endif; ?>
                            </td>
                        <?php endif; ?>

                    </tr>

                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

                    <tr>
                        <td colspan="<?php echo e(auth()->user()->isAdmin() ? 7 : 6); ?>">
                            <?php echo e($showArchived ? 'No archived appointments.' : 'No appointments found.'); ?>

                        </td>
                    </tr>

                <?php endif; ?>

                </tbody>

            </table>

        </div>

        <div class="pagination"><?php echo e($appointments->links()); ?></div>

    </div>

</section>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Regis D\Desktop\Beauty-Aesthetic-Appointment-System\resources\views/admin/appointments.blade.php ENDPATH**/ ?>