<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"></head>
<body style="margin:0;padding:24px;background:#f7eef1;font-family:Arial,Helvetica,sans-serif;color:#2b2226;">
    <div style="max-width:520px;margin:0 auto;background:#ffffff;border-radius:16px;padding:32px;border:1px solid #f0d9e0;">

        <p style="margin:0 0 4px;font-size:12px;letter-spacing:2px;color:#b5657f;font-weight:bold;">M. CARE BEAUTY SERVICES</p>
        <h1 style="margin:0 0 12px;font-size:22px;">Your booking is confirmed &#10024;</h1>

        <p style="margin:0 0 16px;font-size:15px;line-height:1.5;">
            Hi <?php echo e($appointment->user->first_name); ?>, good news! Your appointment has been confirmed. Here are the details:
        </p>

        <?php echo $__env->make('emails.partials-booking-details', ['appointment' => $appointment], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <?php if(filled(config('booking.confirmation_note'))): ?>
            <div style="margin:22px 0 0;padding:16px 18px;border-radius:12px;background:#fbe9ef;border-left:4px solid #b8325f;">
                <p style="margin:0 0 6px;font-size:12px;letter-spacing:1.5px;font-weight:bold;color:#b8325f;">A LITTLE NOTE FROM US</p>
                <p style="margin:0;font-size:14px;line-height:1.6;color:#4a3a40;"><?php echo e(config('booking.confirmation_note')); ?></p>
            </div>
        <?php endif; ?>

        <p style="margin:18px 0 0;font-size:13px;line-height:1.6;color:#5a4b51;">
            Can't make it? Please cancel through your account so someone else can take your slot.
        </p>

        <p style="margin:24px 0 0;font-size:15px;font-style:italic;color:#b8325f;">With love, M. Care Beauty Services &hearts;<br>Glow with Confidence, Naturally</p>
        <p style="margin:8px 0 0;font-size:12px;color:#8a7a80;">This is an automatic message from M. Care Beauty Services.</p>
    </div>
</body>
</html>
<?php /**PATH C:\Users\Regis D\Desktop\Beauty-Aesthetic-Appointment-System\resources\views/emails/booking-confirmed.blade.php ENDPATH**/ ?>