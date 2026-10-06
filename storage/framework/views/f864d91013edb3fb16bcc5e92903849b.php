

<?php $__env->startSection('title', 'My Appointments | M. Cares'); ?>

<?php $__env->startSection('content'); ?>
<section class="page-hero"><div class="container"><span class="eyebrow">APPOINTMENTS</span><h1>My appointment requests</h1><p>Track your upcoming and past clinic appointments.</p></div></section>
<section class="section compact"><div class="container"><div class="dashboard-actions"><a class="primary-button" href="<?php echo e(route('client.appointments.create')); ?>">+ New appointment</a></div>
<div class="table-wrap"><table><thead><tr><th>Date</th><th>Time</th><th>Service</th><th>Status</th><th>Action</th></tr></thead><tbody>
<?php $__empty_1 = true; $__currentLoopData = $appointments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $appointment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
<tr><td><?php echo e($appointment->appointment_date->format('M d, Y')); ?></td><td><?php echo e(\Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A')); ?></td><td><?php echo e($appointment->service->name); ?></td><td><span class="status <?php echo e($appointment->status); ?>"><?php echo e(ucfirst($appointment->status)); ?></span></td><td><?php if(!in_array($appointment->status, ['cancelled','completed'])): ?><form method="POST" action="<?php echo e(route('client.appointments.cancel', $appointment)); ?>" class="inline-form"><?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?><button class="danger-link" onclick="return confirm('Cancel this appointment?')">Cancel</button></form><?php else: ?> — <?php endif; ?></td></tr>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><tr><td colspan="5">No appointment records yet.</td></tr><?php endif; ?>
</tbody></table></div>
<div class="pagination"><?php echo e($appointments->links()); ?></div></div></section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Regis D\Desktop\Beauty-Aesthetic-Appointment-System\resources\views/appointments/index.blade.php ENDPATH**/ ?>