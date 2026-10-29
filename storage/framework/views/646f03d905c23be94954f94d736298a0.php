<?php
    $tier = \App\Support\ClientTier::for(auth()->user());
?>

<div class="panel" style="margin-bottom:20px;">
    <span class="eyebrow">YOUR LEVEL</span>

    <h2 style="margin:6px 0;">
        <?php echo e($tier->key === 'vip' ? '👑 ' : ''); ?><?php echo e($tier->name()); ?>

    </h2>

    <p style="margin:0 0 8px;">
        <?php echo e($tier->completed); ?> completed <?php echo e($tier->completed === 1 ? 'visit' : 'visits'); ?>.
        <?php if($tier->visitsToNext() !== null): ?>
            <?php echo e($tier->visitsToNext()); ?> more to become a <?php echo e($tier->nextName()); ?>.
        <?php else: ?>
            You are at the highest level.
        <?php endif; ?>
    </p>

    <p class="muted" style="margin:0;">
        Booking limits: up to <?php echo e($tier->limit('max_active')); ?>

        upcoming <?php echo e($tier->limit('max_active') === 1 ? 'appointment' : 'appointments'); ?> at a time,
        <?php echo e($tier->limit('max_per_day')); ?> requests per day,
        and bookings up to <?php echo e($tier->limit('advance_days')); ?> days ahead.
    </p>

    <?php if(auth()->user()->cancel_strikes > 0): ?>
        <p style="margin:10px 0 0;font-weight:600;">
            ⚠ You have cancelled <?php echo e(auth()->user()->cancel_strikes); ?>

            of <?php echo e(config('loyalty.cancel_ban_limit', 3)); ?> appointments.
            At <?php echo e(config('loyalty.cancel_ban_limit', 3)); ?> cancellations your account is banned.
        </p>
    <?php endif; ?>
</div>
<?php /**PATH C:\Users\Regis D\Desktop\Beauty-Aesthetic-Appointment-System\resources\views/partials/client-tier.blade.php ENDPATH**/ ?>