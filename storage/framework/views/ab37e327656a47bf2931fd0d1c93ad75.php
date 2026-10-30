

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

                <td>
                    <?php echo e($appointment->service->name); ?>


                    <?php if($appointment->is_package && \App\Support\StaffAppointments::isMine($appointment, $staff)): ?>
                        <?php echo $__env->make('partials.package-sessions', ['appointment' => $appointment], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    <?php endif; ?>
                </td>

                <td>
                    <span class="status <?php echo e($appointment->status); ?>"><?php echo e(ucfirst($appointment->status)); ?></span>
                </td>

                <td>
                    <div class="sa-actions">

                        <button type="button" class="sa-open" data-appointment="<?php echo e(json_encode($payload)); ?>">
                            <?php echo e($payload['canEdit'] ? 'Manage' : 'View details'); ?>

                        </button>

                        <?php if(\App\Support\StaffAppointments::isMine($appointment, $staff)): ?>
                            <?php if($appointment->status === 'completed' && ! $appointment->is_package): ?>
                                <a class="sa-print" href="<?php echo e(route('staff.appointments.receipt', $appointment)); ?>" target="_blank" rel="noopener">🧾 Print receipt</a>
                            <?php endif; ?>

                            <?php if($appointment->service->is_package && $appointment->status !== 'cancelled'): ?>
                                <a class="sa-print" href="<?php echo e(route('staff.appointments.package', $appointment)); ?>" target="_blank" rel="noopener">📋 Package sheet</a>
                            <?php endif; ?>
                        <?php endif; ?>

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


<style>
    .sa-print {
        display: inline-block;
        margin-top: 6px;
        padding: 6px 12px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 700;
        text-decoration: none;
        color: #62444D;
        background: linear-gradient(135deg, #F3BCD2, #FFAFF1);
        white-space: nowrap;
    }
    .sa-print:hover { filter: brightness(1.05); }
</style>
<?php /**PATH C:\Users\Regis D\Desktop\Beauty-Aesthetic-Appointment-System\resources\views/staff/partials/appointment-table.blade.php ENDPATH**/ ?>