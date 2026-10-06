

<?php $__env->startSection('title', 'My Profile | M. Cares'); ?>

<?php $__env->startSection('content'); ?>
<section class="page-hero"><div class="container"><span class="eyebrow">MY PROFILE</span><h1>Keep your client information updated.</h1><p>This information is used when managing your appointments.</p></div></section>
<section class="section compact"><div class="container narrow-panel">
<form method="POST" action="<?php echo e(route('client.profile.update')); ?>" class="form-stack">
<?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
<div class="form-grid two"><label>First name<input name="first_name" value="<?php echo e(old('first_name', $user->first_name)); ?>" required></label><label>Last name<input name="last_name" value="<?php echo e(old('last_name', $user->last_name)); ?>" required></label></div>
<div class="form-grid two"><label>Email<input type="email" name="email" value="<?php echo e(old('email', $user->email)); ?>" required></label><label>Phone<input name="phone" value="<?php echo e(old('phone', $user->phone)); ?>" required></label></div>
<div class="form-grid two"><label>Date of birth<input type="date" name="date_of_birth" value="<?php echo e(old('date_of_birth', optional($user->date_of_birth)->format('Y-m-d'))); ?>"></label><label>Address<input name="address" value="<?php echo e(old('address', $user->address)); ?>"></label></div>
<button class="primary-button" type="submit">Save changes</button>
</form></div></section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Regis D\Desktop\Beauty-Aesthetic-Appointment-System\resources\views/client/profile.blade.php ENDPATH**/ ?>