<?php $__env->startSection('title', 'Manage Appointments | M. Cares'); ?>

<?php $__env->startSection('content'); ?>

<section class="page-hero">
    <div class="container">
        <span class="eyebrow">ADMIN · APPOINTMENTS</span>
        <h1><?php echo e($showArchived ? 'Archived appointments' : 'Manage appointment requests'); ?></h1>
        <p>
            <?php if($showArchived): ?>
                Archived appointments are hidden from the list and from all analytics.
            <?php else: ?>
                Confirm, reschedule, complete, or cancel client requests. Archive cancelled ones to remove them from the analytics.
            <?php endif; ?>
        </p>
    </div>
</section>

<section class="section compact">

    <div class="container">

        
        <?php if(auth()->user()->isAdmin()): ?>
            <div class="dashboard-actions" style="margin-bottom:16px;">
                <?php if($showArchived): ?>
                    <a class="primary-button" href="<?php echo e(route('admin.appointments')); ?>">← Back to appointments</a>
                <?php else: ?>
                    <a class="primary-button" href="<?php echo e(route('admin.appointments', ['archived' => 1])); ?>">
                        🗄 View archive (<?php echo e($archivedCount); ?>)
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if($errors->any()): ?>
            <p style="color:#d64b6b;"><?php echo e($errors->first()); ?></p>
        <?php endif; ?>

        <div class="table-wrap">

            <table>

                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Client</th>
                        <th>Service</th>
                        <th>Status</th>
                        <th>Staff</th>
                        <th>Save</th>
                        <?php if(auth()->user()->isAdmin()): ?>
                            <th>Archive</th>
                        <?php endif; ?>
                    </tr>
                </thead>

                <tbody>

                <?php $__empty_1 = true; $__currentLoopData = $appointments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $appointment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                    <tr>

                        <td>
                            <?php echo e($appointment->appointment_date->format('M d, Y')); ?><br>
                            <?php echo e(\Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A')); ?>

                        </td>

                        <td>
                            <?php echo e($appointment->user->full_name); ?><br>
                            <small><?php echo e($appointment->user->phone); ?></small>
                        </td>

                        <td><?php echo e($appointment->service->name); ?></td>

                        <td>
                            <form method="POST"
                                  action="<?php echo e(auth()->user()->isAdmin() ? route('admin.appointments.update', $appointment) : route('staff.appointments.update', $appointment)); ?>"
                                  class="table-form"
                                  data-save-form="<?php echo e($appointment->id); ?>">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PATCH'); ?>

                                <select name="status">
                                    <option value="pending" <?php if($appointment->status==='pending'): echo 'selected'; endif; ?>>Pending</option>
                                    <option value="confirmed" <?php if($appointment->status==='confirmed'): echo 'selected'; endif; ?>>Confirmed</option>
                                    <option value="completed" <?php if($appointment->status==='completed'): echo 'selected'; endif; ?>>Completed</option>
                                    <option value="rescheduled" <?php if($appointment->status==='rescheduled'): echo 'selected'; endif; ?>>Rescheduled</option>
                                    <option value="cancelled" <?php if($appointment->status==='cancelled'): echo 'selected'; endif; ?>>Cancelled</option>
                                </select>
                        </td>

                        <td>
                                <select name="staff_id">
                                    <option value=""><?php echo e($appointment->with_owner ? 'Owner (requested)' : 'Unassigned'); ?></option>
                                    <?php $__currentLoopData = $staff; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($member->id); ?>" <?php if($appointment->staff_id===$member->id): echo 'selected'; endif; ?>>
                                            <?php echo e($member->user->full_name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                        </td>

                        <td>
                                <div class="save-actions">
                                    <button class="small-button">Save</button>

                                    
                                    <?php if($appointment->admin_saved_at): ?>
                                        <button type="button" class="save-state is-saved" data-saved="1" title="Saved">✓ Saved</button>

                                        
                                        <button type="submit"
                                                class="unsave-button"
                                                formnovalidate
                                                formaction="<?php echo e(auth()->user()->isAdmin() ? route('admin.appointments.unsave', $appointment) : route('staff.appointments.unsave', $appointment)); ?>">
                                            Unsave
                                        </button>
                                    <?php else: ?>
                                        <button type="button" class="save-state is-unsaved" data-saved="0" title="Not saved yet. Click Save on this row">✕ Unsaved</button>
                                    <?php endif; ?>
                                </div>
                            </form>
                        </td>

                        <?php if(auth()->user()->isAdmin()): ?>
                            <td>
                                <?php if($appointment->archived_at): ?>

                                    <form method="POST" action="<?php echo e(route('admin.appointments.restore', $appointment)); ?>" class="inline-form">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('PATCH'); ?>
                                        <button class="small-button">Restore</button>
                                    </form>

                                <?php elseif($appointment->status === 'cancelled'): ?>

                                    <form method="POST" action="<?php echo e(route('admin.appointments.archive', $appointment)); ?>" class="inline-form">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('PATCH'); ?>
                                        <button class="small-button"
                                                onclick="return confirm('Archive this cancelled appointment? It will be removed from the analytics.')">
                                            🗄 Archive
                                        </button>
                                    </form>

                                <?php else: ?>
                                    —
                                <?php endif; ?>
                            </td>
                        <?php endif; ?>

                    </tr>

                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

                    <tr>
                        <td colspan="<?php echo e(auth()->user()->isAdmin() ? 7 : 6); ?>">
                            <?php echo e($showArchived ? 'No archived appointments.' : 'No appointments found.'); ?>

                        </td>
                    </tr>

                <?php endif; ?>

                </tbody>

            </table>

        </div>

        <div class="pagination"><?php echo e($appointments->links()); ?></div>

    </div>

</section>

<style>
    .save-actions { display: flex; gap: 8px; align-items: center; flex-wrap: wrap; }

    .save-state {
        padding: 9px 14px;
        border: 0;
        border-radius: 12px;
        font-size: 13px;
        font-weight: 700;
        color: #fff;
        cursor: default;
        transition: background .2s ease, transform .2s ease;
    }
    .save-state.is-saved   { background: #2e8b57; }
    .save-state.is-unsaved { background: #e5484d; cursor: pointer; }
    .save-state.is-unsaved:hover { background: #c93a3f; }
    .unsave-button {
        padding: 9px 14px;
        border: 0;
        border-radius: 12px;
        font-size: 13px;
        font-weight: 700;
        color: #fff;
        background: #e5484d;
        cursor: pointer;
    }
    .unsave-button:hover { background: #c93a3f; }

    .save-state.just-saved { animation: save-pop .8s ease; }

    @keyframes save-pop {
        0%   { transform: scale(1); }
        40%  { transform: scale(1.18); }
        100% { transform: scale(1); }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {

    var justSaved = null;
    try { justSaved = sessionStorage.getItem('appointment-just-saved'); sessionStorage.removeItem('appointment-just-saved'); } catch (e) {}

    document.querySelectorAll('[data-save-form]').forEach(function (form) {

        // the form starts in one table cell and its fields sit in the next cells,
        // so look in the whole row (and use form.elements) instead of inside the form
        var row     = form.closest('tr');
        var pill    = row.querySelector('.save-state');
        var fields  = Array.prototype.filter.call(form.elements, function (el) { return el.tagName === 'SELECT'; });

        function changed() {
            return fields.some(function (field) {
                return Array.prototype.some.call(field.options, function (option) {
                    return option.selected !== option.defaultSelected;
                });
            });
        }

        var wasSaved = pill.dataset.saved === '1';

        function refresh() {
            // saved only when this row was saved AND nothing was changed since
            if (wasSaved && !changed()) {
                pill.className = 'save-state is-saved';
                pill.textContent = '✓ Saved';
                pill.title = 'Saved';
            } else {
                pill.className = 'save-state is-unsaved';
                pill.textContent = '✕ Unsaved';
                pill.title = 'Not saved yet. Click Save on this row';
            }
        }

        fields.forEach(function (field) { field.addEventListener('change', refresh); });

        form.addEventListener('submit', function () {
            try { sessionStorage.setItem('appointment-just-saved', form.dataset.saveForm); } catch (e) {}
        });

        if (justSaved && justSaved === form.dataset.saveForm) {
            pill.classList.add('just-saved');
        }
    });
});
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Regis D\Desktop\Beauty-Aesthetic-Appointment-System\resources\views/admin/appointments.blade.php ENDPATH**/ ?>