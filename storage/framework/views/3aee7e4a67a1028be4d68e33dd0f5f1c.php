

<?php $__env->startSection('title', 'Book Appointment | M. Cares'); ?>

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

        <?php echo $__env->make('partials.perks-banner', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

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
            name="notes"
            rows="4"
            maxlength="1000"
            placeholder="Optional: tell the clinic anything important about your request."
        ><?php echo e(old('notes')); ?></textarea>
    </label>

    <button
        class="primary-button full"
        type="submit"
    >
        Send appointment request
    </button>

</form>

    </div>

</section>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Regis D\Desktop\Beauty-Aesthetic-Appointment-System\resources\views/appointments/create.blade.php ENDPATH**/ ?>