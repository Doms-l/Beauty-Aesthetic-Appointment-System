

<div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>Date &amp; time</th>
                <th>Client</th>
                <th>Service</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $appointment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

            <?php $payload = \App\Support\StaffAppointments::payload($appointment, $staff); ?>

            <tr>
                <td>
                    <?php echo e($appointment->appointment_date->format('M d, Y')); ?><br>
                    <strong><?php echo e(\Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A')); ?></strong>
                </td>

                <td>
                    <?php echo e($appointment->user->full_name); ?><br>
                    <small><?php echo e($appointment->user->phone); ?></small>
                </td>

                <td><?php echo e($appointment->service->name); ?></td>

                <td>
                    <span class="status <?php echo e($appointment->status); ?>"><?php echo e(ucfirst($appointment->status)); ?></span>
                </td>

                <td>
                    <div class="sa-actions">

                        <button type="button" class="sa-open" data-appointment="<?php echo e(json_encode($payload)); ?>">
                            <?php echo e($payload['canEdit'] ? 'Manage' : 'View details'); ?>

                        </button>

                        <?php if($payload['canClaim']): ?>
                            <form method="POST" action="<?php echo e($payload['claimUrl']); ?>" class="inline-form">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PATCH'); ?>
                                <button class="sa-claim"
                                        onclick="return confirm('Take this appointment? It will be assigned to you.')">
                                    Take it
                                </button>
                            </form>
                        <?php endif; ?>

                    </div>
                </td>
            </tr>

        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

            <tr>
                <td colspan="5"><?php echo e($empty ?? 'Nothing here.'); ?></td>
            </tr>

        <?php endif; ?>
        </tbody>
    </table>
</div>
<?php /**PATH C:\Users\Regis D\Desktop\Beauty-Aesthetic-Appointment-System\resources\views/staff/partials/appointment-table.blade.php ENDPATH**/ ?>