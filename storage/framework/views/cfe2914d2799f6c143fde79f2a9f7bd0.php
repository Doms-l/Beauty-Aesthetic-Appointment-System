<?php
    $a        = $appointment;
    $timeText = \Carbon\Carbon::parse($a->appointment_time)->format('h:i A');
    $who      = $a->staff?->user?->full_name
                ?: ($a->with_owner ? 'The Owner (requested)' : 'Any available staff');
?>
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="font-size:14px;line-height:1.5;border-collapse:collapse;">
    <tr><td style="padding:8px 0;color:#8a7a80;width:120px;border-bottom:1px solid #f3e4e9;">Service</td><td style="padding:8px 0;font-weight:bold;border-bottom:1px solid #f3e4e9;"><?php echo e($a->service->name); ?></td></tr>
    <tr><td style="padding:8px 0;color:#8a7a80;border-bottom:1px solid #f3e4e9;">Date</td><td style="padding:8px 0;font-weight:bold;border-bottom:1px solid #f3e4e9;"><?php echo e($a->appointment_date->format('l, M d, Y')); ?></td></tr>
    <tr><td style="padding:8px 0;color:#8a7a80;border-bottom:1px solid #f3e4e9;">Time</td><td style="padding:8px 0;font-weight:bold;border-bottom:1px solid #f3e4e9;"><?php echo e($timeText); ?></td></tr>
    <tr><td style="padding:8px 0;color:#8a7a80;border-bottom:1px solid #f3e4e9;">Served by</td><td style="padding:8px 0;font-weight:bold;border-bottom:1px solid #f3e4e9;"><?php echo e($who); ?></td></tr>
    <?php if($a->notes): ?>
        <tr><td style="padding:8px 0;color:#8a7a80;border-bottom:1px solid #f3e4e9;vertical-align:top;">Notes</td><td style="padding:8px 0;border-bottom:1px solid #f3e4e9;"><?php echo e($a->notes); ?></td></tr>
    <?php endif; ?>
</table>
<?php /**PATH C:\Users\Regis D\Desktop\Beauty-Aesthetic-Appointment-System\resources\views/emails/partials-booking-details.blade.php ENDPATH**/ ?>