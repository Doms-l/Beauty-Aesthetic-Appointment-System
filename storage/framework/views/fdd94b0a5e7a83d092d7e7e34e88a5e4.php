
<?php
    $cpTotal = (int) $appointment->service->sessions;
    $cpDone  = $appointment->sessions;
    $cpNext  = $appointment->next_visit_date;
    $cpOpen  = $appointment->canLogSession();
    $cpNo    = $cpDone->count() + 1;
?>

<div class="cpkg">
    <div class="cpkg-head">
        <strong>Session <?php echo e(min($cpDone->count() + ($cpOpen ? 1 : 0), $cpTotal)); ?> of <?php echo e($cpTotal); ?></strong>
        <span class="cpkg-dots" aria-hidden="true">
            <?php for($i = 1; $i <= $cpTotal; $i++): ?>
                <i class="<?php echo e($i <= $cpDone->count() ? 'on' : ''); ?>"></i>
            <?php endfor; ?>
        </span>
    </div>

    <div class="cpkg-line"><?php echo e($cpDone->count()); ?> of <?php echo e($cpTotal); ?> done</div>

    <?php if($cpOpen): ?>
        <div class="cpkg-next">
            Next visit (session <?php echo e($cpNo); ?>):
            <?php if($cpNext): ?>
                <b><?php echo e($cpNext->format('M d, Y')); ?><?php if($cpDone->isEmpty()): ?>, <?php echo e(\Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A')); ?><?php endif; ?></b>
            <?php else: ?>
                <b>Not scheduled yet</b> <span class="cpkg-muted">— the clinic will set the date</span>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <div class="cpkg-next">
            <?php if($appointment->status === 'cancelled'): ?> Package cancelled. <?php else: ?> All sessions finished. <?php endif; ?>
        </div>
    <?php endif; ?>

    <?php $__currentLoopData = $cpDone; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $session): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="cpkg-line cpkg-muted">✓ Session <?php echo e($session->session_no); ?> · <?php echo e($session->session_date->format('M d, Y')); ?></div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>

<?php if (! $__env->hasRenderedOnce('8966cc66-64c2-45d8-aa46-8aabf16184e7')): $__env->markAsRenderedOnce('8966cc66-64c2-45d8-aa46-8aabf16184e7'); ?>
<style>
    .cpkg { margin-top: 8px; padding: 8px 10px; border-radius: 12px; border: 1px solid #E8AECF; background: rgba(243, 188, 210, .18); font-size: 12px; min-width: 190px; color: var(--text); }
    .cpkg-head { display: flex; align-items: center; justify-content: space-between; gap: 8px; }
    .cpkg-dots { display: inline-flex; gap: 4px; }
    .cpkg-dots i { width: 10px; height: 10px; border-radius: 50%; border: 1.5px solid #D6B36A; background: transparent; }
    .cpkg-dots i.on { background: #2e9a5d; border-color: #2e9a5d; }
    .cpkg-line { margin-top: 4px; }
    .cpkg-next { margin-top: 6px; padding-top: 6px; border-top: 1px dotted #E8AECF; }
    .cpkg-muted { color: var(--text-muted); }
</style>
<?php endif; ?>
<?php /**PATH C:\Users\Regis D\Desktop\Beauty-Aesthetic-Appointment-System\resources\views/partials/client-package-progress.blade.php ENDPATH**/ ?>