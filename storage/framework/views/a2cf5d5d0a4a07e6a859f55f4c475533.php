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

        
        <form method="GET" action="<?php echo e(route('admin.appointments')); ?>" class="date-filter">
            <?php if($showArchived): ?>
                <input type="hidden" name="archived" value="1">
            <?php endif; ?>
            <label for="filter-date">📅 Show bookings on</label>
            <input type="date" id="filter-date" name="date" value="<?php echo e($selectedDate); ?>"
                   onchange="this.form.submit()">
            <a class="date-filter-btn"
               href="<?php echo e(route('admin.appointments', array_filter(['archived' => $showArchived ? 1 : null, 'date' => now()->toDateString()]))); ?>">Today</a>
            <?php if($selectedDate): ?>
                <a class="date-filter-btn clear"
                   href="<?php echo e(route('admin.appointments', array_filter(['archived' => $showArchived ? 1 : null]))); ?>">✕ Show all dates</a>
            <?php endif; ?>
        </form>

        <?php if($selectedDate): ?>
            <p class="date-filter-note">
                Showing <strong><?php echo e($appointments->total()); ?></strong>
                <?php echo e($appointments->total() === 1 ? 'booking' : 'bookings'); ?> on
                <strong><?php echo e(\Carbon\Carbon::parse($selectedDate)->format('l, M d, Y')); ?></strong>
            </p>
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

                    <?php
                        $clientTier = \App\Support\ClientTier::for($appointment->user);

                        // everything the "Details" window shows for this request
                        $details = [
                            'client'     => $appointment->user->full_name,
                            'email'      => $appointment->user->email,
                            'phone'      => $appointment->user->phone ?: '-',
                            'level'      => $clientTier->name() . ' (' . $clientTier->completed . ' completed ' . ($clientTier->completed === 1 ? 'visit' : 'visits') . ')',
                            'cancelled'  => (int) $appointment->user->cancel_strikes . ' of ' . config('loyalty.cancel_ban_limit', 3),
                            'service'    => $appointment->service->name,
                            'category'   => $appointment->service->category ?: '-',
                            'price'      => $appointment->service->display_price,
                            'duration'   => $appointment->service->duration_minutes ? $appointment->service->duration_minutes . ' minutes' : '-',
                            'date'       => $appointment->appointment_date->format('l, M d, Y'),
                            'time'       => \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A'),
                            'booked'     => $appointment->created_at->format('M d, Y, h:i A'),
                            'provider'   => $appointment->provider_label . ($appointment->with_owner && ! $appointment->staff_id ? ' (requested)' : ''),
                            'status'     => ucfirst($appointment->status),
                            'notes'      => trim((string) $appointment->notes) !== '' ? $appointment->notes : '',
                        ];
                    ?>

                    <tr>

                        <td>
                            <?php echo e($appointment->appointment_date->format('M d, Y')); ?><br>
                            <?php echo e(\Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A')); ?><br>

                            <button type="button"
                                    class="details-button"
                                    data-details="<?php echo e(json_encode($details)); ?>">
                                View details
                            </button>
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
                            <?php echo e($selectedDate ? 'No bookings on this date.' : ($showArchived ? 'No archived appointments.' : 'No appointments found.')); ?>

                        </td>
                    </tr>

                <?php endif; ?>

                </tbody>

            </table>

        </div>

        <div class="pagination"><?php echo e($appointments->links()); ?></div>

    </div>

</section>


<div id="details-modal" class="details-modal" hidden>
    <div class="details-backdrop" data-close></div>

    <div class="details-card" role="dialog" aria-modal="true" aria-labelledby="details-title">

        <button type="button" class="details-close" data-close aria-label="Close">&times;</button>

        <span class="eyebrow">APPOINTMENT REQUEST</span>
        <h2 id="details-title">Request details</h2>

        <div class="details-group">
            <h3>Client</h3>
            <dl>
                <dt>Name</dt><dd data-field="client"></dd>
                <dt>Email</dt><dd data-field="email"></dd>
                <dt>Phone</dt><dd data-field="phone"></dd>
                <dt>Level</dt><dd data-field="level"></dd>
                <dt>Cancelled</dt><dd data-field="cancelled"></dd>
            </dl>
        </div>

        <div class="details-group">
            <h3>Service</h3>
            <dl>
                <dt>Service</dt><dd data-field="service"></dd>
                <dt>Type</dt><dd data-field="category"></dd>
                <dt>Price</dt><dd data-field="price"></dd>
                <dt>Duration</dt><dd data-field="duration"></dd>
            </dl>
        </div>

        <div class="details-group">
            <h3>Request</h3>
            <dl>
                <dt>Date</dt><dd data-field="date"></dd>
                <dt>Time</dt><dd data-field="time"></dd>
                <dt>Booked on</dt><dd data-field="booked"></dd>
                <dt>Serve by</dt><dd data-field="provider"></dd>
                <dt>Status</dt><dd data-field="status"></dd>
            </dl>
        </div>

        <div class="details-group">
            <h3>Client notes</h3>
            <p class="details-notes" data-field="notes"></p>
        </div>

    </div>
</div>

<style>
    .date-filter { display:flex; align-items:center; gap:10px; flex-wrap:wrap; margin-bottom:14px; }
    .date-filter label { font-weight:700; color:var(--heading); }
    .date-filter input[type="date"] {
        padding:10px 12px; border:1px solid var(--line); border-radius:12px;
        background:var(--surface); color:var(--heading); font:inherit;
    }
    .date-filter-btn {
        padding:10px 16px; border-radius:12px; border:1px solid var(--line);
        background:var(--surface-soft); color:var(--heading); font-weight:700; text-decoration:none;
    }
    .date-filter-btn.clear { background:var(--mauve); color:#fff; border-color:transparent; }
    .date-filter-note { margin:0 0 14px; color:var(--heading); }
    html.dark-mode .date-filter input[type="date"] { color-scheme: dark; }
</style>

<style>
    .details-button {
        margin-top: 8px;
        padding: 6px 12px;
        border: 1px solid var(--line);
        border-radius: 999px;
        background: var(--surface-soft);
        color: var(--heading);
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
    }
    .details-button:hover { background: var(--mauve); color: #fff; border-color: var(--mauve); }

    .details-modal { position: fixed; inset: 0; z-index: 200; display: flex; align-items: center; justify-content: center; padding: 16px; }
    .details-modal[hidden] { display: none; }
    .details-backdrop { position: absolute; inset: 0; background: rgba(20, 10, 16, .6); }

    .details-card {
        position: relative;
        width: min(560px, 100%);
        max-height: calc(100vh - 32px);
        overflow-y: auto;
        padding: 26px 24px 24px;
        border-radius: 24px;
        background: var(--surface);
        border: 1px solid var(--line);
        box-shadow: 0 24px 60px rgba(0, 0, 0, .35);
    }
    .details-card h2 { margin: 4px 0 14px; color: var(--heading); }
    .details-card h3 { margin: 0 0 6px; font-size: 12px; letter-spacing: 1.5px; text-transform: uppercase; color: var(--mauve); }

    .details-close {
        position: absolute; top: 12px; right: 14px;
        width: 40px; height: 40px;
        border: 0; border-radius: 50%;
        background: var(--surface-soft); color: var(--heading);
        font-size: 24px; line-height: 1; cursor: pointer;
    }

    .details-group { padding: 12px 0; border-top: 1px solid var(--line); }
    .details-group dl { display: grid; grid-template-columns: 110px minmax(0, 1fr); gap: 6px 12px; margin: 0; }
    .details-group dt { color: var(--text-muted, #7d7074); font-size: 13px; }
    .details-group dd { margin: 0; font-weight: 600; word-break: break-word; }
    .details-notes { margin: 0; white-space: pre-wrap; word-break: break-word; }
    .details-notes.is-empty { color: var(--text-muted, #7d7074); font-style: italic; }

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

    // ----- details window -----
    var modal = document.getElementById('details-modal');

    function closeDetails() { modal.hidden = true; document.body.style.overflow = ''; }

    document.querySelectorAll('.details-button').forEach(function (button) {
        button.addEventListener('click', function () {
            var data = {};
            try { data = JSON.parse(button.dataset.details); } catch (e) {}

            modal.querySelectorAll('[data-field]').forEach(function (el) {
                var value = data[el.dataset.field];

                if (el.dataset.field === 'notes') {
                    el.textContent = value ? value : 'The client did not add any notes.';
                    el.classList.toggle('is-empty', !value);
                } else {
                    el.textContent = value || '-';
                }
            });

            modal.hidden = false;
            document.body.style.overflow = 'hidden';
            modal.querySelector('.details-close').focus();
        });
    });

    modal.querySelectorAll('[data-close]').forEach(function (el) { el.addEventListener('click', closeDetails); });
    document.addEventListener('keydown', function (event) { if (event.key === 'Escape' && !modal.hidden) { closeDetails(); } });

    // ----- Saved / Unsaved buttons -----
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