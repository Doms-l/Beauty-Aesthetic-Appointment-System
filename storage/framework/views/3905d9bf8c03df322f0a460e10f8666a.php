

<?php $__env->startSection('title', 'Manage Staff | M. Cares'); ?>

<?php $__env->startSection('content'); ?>
<section class="page-hero"><div class="container"><span class="eyebrow">ADMIN · STAFF</span><h1>Clinic staff</h1><p>Create staff accounts and keep assigned roles organized.</p></div></section>
<section class="section compact"><div class="container admin-two-col">
<div class="panel"><span class="eyebrow">ADD STAFF</span><h2>Staff account</h2><form method="POST" action="<?php echo e(route('admin.staff.store')); ?>" class="form-stack"><?php echo csrf_field(); ?><div class="form-grid two"><label>First name<input name="first_name" required></label><label>Last name<input name="last_name" required></label></div><label>Email<input type="email" name="email" required></label><label>Phone<input type="tel" name="phone" value="<?php echo e(old('phone')); ?>" required maxlength="11" minlength="11" inputmode="numeric" pattern="[0-9]{11}" placeholder="09XXXXXXXXX" oninput="this.value = this.value.replace(/\D/g, '').slice(0, 11);" onkeydown="if (event.ctrlKey || event.metaKey) { return true; } return (event.key.length > 1) || (event.key >= '0' && event.key <= '9');" onpaste="event.preventDefault(); this.value = (event.clipboardData.getData('text') || '').replace(/\D/g, '').slice(0, 11);"></label><div class="form-grid two"><label>Position<input name="position" placeholder="Beauty Specialist" required></label><label>Specialization<input name="specialization"></label></div><div class="form-grid two"><label>Password<div class="password-input-wrapper"><input id="staff_password" type="password" name="password" minlength="8" required autocomplete="new-password"><button type="button" class="password-toggle" onclick="togglePassword('staff_password', this)" aria-label="Show password">👁</button></div></label><label>Confirm password<div class="password-input-wrapper"><input id="staff_password_confirmation" type="password" name="password_confirmation" minlength="8" required autocomplete="new-password"><button type="button" class="password-toggle" onclick="togglePassword('staff_password_confirmation', this)" aria-label="Show password">👁</button></div></label></div><button class="primary-button full">Create staff</button></form></div>
<div class="panel"><span class="eyebrow">STAFF LIST</span><h2>Current staff</h2><div class="admin-list"><?php $__empty_1 = true; $__currentLoopData = $staff; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><div class="admin-item"><div><strong><?php echo e($member->user->full_name); ?></strong><p><?php echo e($member->position); ?> · <?php echo e($member->specialization ?: 'General'); ?></p><small><?php echo e($member->user->email); ?></small></div><span class="status <?php echo e($member->is_available ? 'confirmed' : 'cancelled'); ?>"><?php echo e($member->is_available ? 'Available' : 'Unavailable'); ?></span></div><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><p>No staff yet.</p><?php endif; ?></div></div>
</div></section>

<script>
    function togglePassword(inputId, button) {

        const input = document.getElementById(inputId);

        if (input.type === 'password') {
            input.type = 'text';
            button.textContent = '🙈';
            button.setAttribute('aria-label', 'Hide password');
        } else {
            input.type = 'password';
            button.textContent = '👁';
            button.setAttribute('aria-label', 'Show password');
        }
    }
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Regis D\Desktop\Beauty-Aesthetic-Appointment-System\resources\views/admin/staff.blade.php ENDPATH**/ ?>