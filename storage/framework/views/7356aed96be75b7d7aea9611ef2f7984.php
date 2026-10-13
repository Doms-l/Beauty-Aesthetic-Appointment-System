
<?php if($link->icon_path): ?>
    <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false"><path d="<?php echo e($link->icon_path); ?>"/></svg>
<?php else: ?>
    <?php echo e(strtoupper(mb_substr($link->label, 0, 1))); ?>

<?php endif; ?>
<?php /**PATH C:\Users\Regis D\Desktop\Beauty-Aesthetic-Appointment-System\resources\views/partials/social-icon.blade.php ENDPATH**/ ?>