

<?php $__env->startSection('title', 'Book Appointment | M. Care'); ?>

<?php $__env->startSection('content'); ?>

<section class="page-hero">
    <div class="container">
        <span class="eyebrow">BOOKING</span>

        <h1>Request an appointment.</h1>

        <p>
            Select your preferred service, date, and time.
            The clinic will confirm your request.
        </p>
    </div>
</section>

<section class="section compact">

    <div class="container narrow-panel">

        <?php echo $__env->make('partials.client-tier', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <?php echo $__env->make('partials.perks-banner', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <?php if(isset($promo)): ?>
            <?php if($promo->isBookable()): ?>
                <div class="promo-notice">
                    <strong>&#127872; <?php echo e($promo->title); ?></strong>
                    <?php if($promo->perk_line): ?><br><?php echo e($promo->perk_line); ?><?php endif; ?>
                    <br><small>Each client can avail this promo once. Your slot is held when you send the request; cancelling gives it back.</small>
                </div>
            <?php else: ?>
                <div class="promo-notice promo-notice-closed">
                    <strong><?php echo e($promo->title); ?></strong><br>
                    <?php echo e($promo->hasEnded() ? 'This promo has ended.' : ($promo->isSoldOut() ? 'All promo slots are taken.' : 'This promo is not available right now.')); ?>

                </div>
            <?php endif; ?>
        <?php endif; ?>

        <form
    method="POST"
    action="<?php echo e(route('client.appointments.store')); ?>"
    class="form-stack"
>

    <?php echo csrf_field(); ?>

    <div
        id="appointment-picker"
        data-services='<?php echo json_encode($services, 15, 512) ?>'
        data-selected-service="<?php echo e(request('service')); ?>"
    ></div>

    <?php echo $__env->make('partials.provider-picker', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <label>
        Additional notes

        <textarea
            id="booking-notes"
            name="notes"
            rows="4"
            maxlength="1000"
            placeholder="Optional: tell the clinic anything important about your request."
        ><?php echo e(old('notes')); ?></textarea>
    </label>

    <p class="form-help" style="margin-top:-8px;">
        Reminder: you can type up to <strong>1000 characters</strong>
        (letters, numbers, spaces and symbols). Keep it short, for example
        allergies, skin concerns or special requests. Do not write passwords
        or payment details here.
        <br>
        <span id="booking-notes-count" style="font-weight:700;">0 / 1000</span> characters used
    </p>

    <button
        class="primary-button full"
        type="submit"
    >
        Send appointment request
    </button>

</form>

    </div>

</section>

<style>
    .promo-notice { margin-bottom: 18px; padding: 14px 18px; border-radius: 16px; border: 1px solid #d6b36a; background: rgba(214, 179, 106, .12); line-height: 1.5; }
    .promo-notice-closed { border-color: #b4534f; background: rgba(180, 83, 79, .12); }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var box = document.getElementById('booking-notes');
    var count = document.getElementById('booking-notes-count');

    if (!box || !count) return;

    function update() {
        var used = box.value.length;
        var max = box.maxLength > 0 ? box.maxLength : 1000;

        count.textContent = used + ' / ' + max;
        count.style.color = used >= max ? '#e5484d' : (used >= max * 0.9 ? '#e9a23b' : '');
    }

    box.addEventListener('input', update);
    update();
});
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Regis D\Desktop\Beauty-Aesthetic-Appointment-System\resources\views/appointments/create.blade.php ENDPATH**/ ?>