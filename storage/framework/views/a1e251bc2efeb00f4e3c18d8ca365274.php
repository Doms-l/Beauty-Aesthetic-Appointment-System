<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"></head>
<body style="margin:0;padding:24px;background:#f7eef1;font-family:Arial,Helvetica,sans-serif;color:#2b2226;">
    <div style="max-width:520px;margin:0 auto;background:#ffffff;border-radius:16px;padding:32px;border:1px solid #f0d9e0;">

        <p style="margin:0 0 4px;font-size:12px;letter-spacing:2px;color:#b5657f;font-weight:bold;">M. CARE BEAUTY SERVICES</p>
        <h1 style="margin:0 0 12px;font-size:22px;">New booking received</h1>

        <p style="margin:0 0 16px;font-size:15px;line-height:1.5;">
            <b><?php echo e($appointment->user->full_name); ?></b> booked an appointment
            <?php if($audience === 'owner'): ?>
                and it is waiting for confirmation.
            <?php elseif($appointment->staff_id): ?>
                with you. Please review and confirm it.
            <?php else: ?>
                with no staff preference. Any available staff member can take it.
            <?php endif; ?>
        </p>

        <?php echo $__env->make('emails.partials-booking-details', ['appointment' => $appointment], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <p style="margin:16px 0 0;font-size:14px;line-height:1.6;color:#5a4b51;">
            Client contact: <?php echo e($appointment->user->phone ?: '-'); ?> &middot; <?php echo e($appointment->user->email); ?>

        </p>

        <p style="margin:24px 0 0;text-align:center;">
            <a href="<?php echo e($link); ?>" style="display:inline-block;padding:12px 26px;border-radius:999px;background:#f3bcd2;color:#62444d;font-weight:bold;text-decoration:none;">
                Open appointments
            </a>
        </p>

        <p style="margin:24px 0 0;font-size:12px;color:#8a7a80;">You are getting this because you are on the M. Care booking notification list.</p>
    </div>
</body>
</html>
<?php /**PATH C:\Users\Regis D\Desktop\Beauty-Aesthetic-Appointment-System\resources\views/emails/new-booking.blade.php ENDPATH**/ ?>