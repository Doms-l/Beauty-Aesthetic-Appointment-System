<?php $__env->startSection('title', 'Manage Appointments | M. Care'); ?>

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
                <?php if(! $showArchived && ($walkInReady ?? false)): ?>
                    <button type="button" class="primary-button" id="open-walkin">➕ Add walk-in</button>
                <?php endif; ?>
                <?php if($showArchived || ($historyOnly ?? false)): ?>
                    <a class="primary-button" href="<?php echo e(route('admin.appointments')); ?>">← Back to appointments</a>
                <?php else: ?>
                    <a class="primary-button" href="<?php echo e(route('admin.appointments', ['view' => 'history'])); ?>">
                        ✅ Completed &amp; past (<?php echo e($historyCount ?? 0); ?>)
                    </a>
                <?php endif; ?>
                <?php if(! $showArchived && ! ($historyOnly ?? false)): ?>
                    <a class="primary-button" href="<?php echo e(route('admin.appointments', ['archived' => 1])); ?>">
                        🗄 View archive (<?php echo e($archivedCount); ?>)
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if(auth()->user()->isAdmin() && ! ($walkInReady ?? false)): ?>
            <div class="new-alert">
                <span class="new-alert-icon">⚠️</span>
                <div class="new-alert-text">
                    <strong>Walk-in and Add service are not turned on yet</strong>
                    <span>Run <code>php artisan migrate</code> in the project folder, then refresh this page.</span>
                </div>
            </div>
        <?php endif; ?>

        
        <?php if($historyOnly ?? false): ?>
            <p class="date-filter-note">
                ✅ Showing <strong>completed and past bookings</strong>, newest first
                (<strong><?php echo e($appointments->total()); ?></strong> <?php echo e($appointments->total() === 1 ? 'booking' : 'bookings'); ?>).
            </p>
        <?php endif; ?>

        <?php if(! $showArchived && ! ($historyOnly ?? false)): ?>
            <?php if($newCount > 0): ?>
                <div class="new-alert">
                    <span class="new-alert-icon">🔔</span>
                    <div class="new-alert-text">
                        <strong><?php echo e($newCount); ?> new <?php echo e($newCount === 1 ? 'appointment' : 'appointments'); ?> to review</strong>
                        <span>Pending requests are marked <b class="new-pill">NEW</b> below.</span>
                    </div>
                    <?php if($pendingOnly): ?>
                        <a class="new-alert-btn" href="<?php echo e(route('admin.appointments')); ?>">Show all</a>
                    <?php else: ?>
                        <a class="new-alert-btn" href="<?php echo e(route('admin.appointments', ['status' => 'pending'])); ?>">Show only new</a>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <div class="new-alert none">✅ No new appointments waiting for review.</div>
            <?php endif; ?>
        <?php endif; ?>

        <?php if($errors->any()): ?>
            <p style="color:#d64b6b;"><?php echo e($errors->first()); ?></p>
        <?php endif; ?>

        
        <?php
            $keep = array_filter([
                'archived' => $showArchived ? 1 : null,
                'view'     => ($historyOnly ?? false) ? 'history' : null,
            ]);
            $statusLabels = [
                'pending'     => 'Pending',
                'confirmed'   => 'Confirmed',
                'completed'   => 'Completed',
                'rescheduled' => 'Rescheduled',
                'cancelled'   => 'Cancelled',
            ];
        ?>

        <form method="GET" action="<?php echo e(route('admin.appointments')); ?>" class="date-filter">
            <?php $__currentLoopData = $keep; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $name => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <input type="hidden" name="<?php echo e($name); ?>" value="<?php echo e($value); ?>">
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            <label for="filter-date">📅 Show bookings on</label>
            <input type="date" id="filter-date" name="date" value="<?php echo e($selectedDate); ?>"
                   onchange="this.form.submit()">

            <label for="filter-status">Status</label>
            <select id="filter-status" name="status" class="status-filter" onchange="this.form.submit()">
                <option value="">All statuses</option>
                <?php $__currentLoopData = $statusLabels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($value); ?>" <?php if(($statusFilter ?? null) === $value): echo 'selected'; endif; ?>><?php echo e($label); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>

            <a class="date-filter-btn"
               href="<?php echo e(route('admin.appointments', array_filter($keep + ['date' => now()->toDateString(), 'status' => $statusFilter ?? null]))); ?>">Today</a>

            <?php if($selectedDate || ($statusFilter ?? null)): ?>
                <a class="date-filter-btn clear" href="<?php echo e(route('admin.appointments', $keep)); ?>">✕ Clear filters</a>
            <?php endif; ?>
        </form>

        <?php if($selectedDate || ($statusFilter ?? null)): ?>
            <p class="date-filter-note">
                Showing <strong><?php echo e($appointments->total()); ?></strong>
                <?php if($statusFilter ?? null): ?><strong><?php echo e(strtolower($statusLabels[$statusFilter])); ?></strong><?php endif; ?>
                <?php echo e($appointments->total() === 1 ? 'booking' : 'bookings'); ?>

                <?php if($selectedDate): ?>
                    on <strong><?php echo e(\Carbon\Carbon::parse($selectedDate)->format('l, M d, Y')); ?></strong>
                <?php endif; ?>
            </p>
        <?php endif; ?>

        <div class="table-wrap">

            <table>

                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Client</th>
                        <th>Service</th>
                        <th>Price</th>
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
                            'email'      => $appointment->user->is_walk_in ? 'Walk-in (no account)' : $appointment->user->email,
                            'phone'      => $appointment->user->phone ?: '-',
                            'level'      => $clientTier->name() . ' (' . $clientTier->completed . ' completed ' . ($clientTier->completed === 1 ? 'visit' : 'visits') . ')',
                            'cancelled'  => (int) $appointment->user->cancel_strikes . ' of ' . config('loyalty.cancel_ban_limit', 3),
                            'service'    => $appointment->service->name . $appointment->extra_items->map(fn ($e) => ' + ' . $e->service->name)->implode(''),
                            'category'   => $appointment->service->category ?: '-',
                            'price'      => $appointment->extra_items->isEmpty()
                                ? $appointment->service->display_price
                                : '₱' . number_format($appointment->total_price, 2) . ' total (' . $appointment->service->display_price . $appointment->extra_items->map(fn ($e) => ' + ₱' . number_format((float) $e->price, 0))->implode('') . ')',
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
                            <?php echo e(\Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A')); ?>

                            <?php if($appointment->status === 'pending' && ! $appointment->archived_at && $appointment->appointment_date->gte(today())): ?>
                                <br><span class="new-pill">NEW</span>
                            <?php endif; ?>
                            <?php if($appointment->is_walk_in): ?>
                                <br><span class="walkin-pill">WALK-IN</span>
                            <?php endif; ?><br>

                            <button type="button"
                                    class="details-button"
                                    data-details="<?php echo e(json_encode($details)); ?>">
                                View details
                            </button>

                            <?php
                                $isAdminUser = auth()->user()->isAdmin();
                                $receiptUrl  = $isAdminUser ? route('admin.appointments.receipt', $appointment) : route('staff.appointments.receipt', $appointment);
                                $packageUrl  = $isAdminUser ? route('admin.appointments.package', $appointment) : route('staff.appointments.package', $appointment);
                            ?>

                            
                            <?php if($appointment->status === 'completed' && ! $appointment->is_package): ?>
                                <a class="print-link" href="<?php echo e($receiptUrl); ?>" target="_blank" rel="noopener">🧾 Print receipt</a>
                            <?php endif; ?>

                            <?php if($appointment->service->is_package && $appointment->status !== 'cancelled' && ! $appointment->archived_at): ?>
                                <a class="print-link" href="<?php echo e($packageUrl); ?>" target="_blank" rel="noopener">📋 Package sheet</a>
                            <?php endif; ?>
                        </td>

                        <td>
                            <?php echo e($appointment->user->full_name); ?><br>
                            <small><?php echo e($appointment->user->phone); ?></small>
                        </td>

                        <td>
                            <?php echo e($appointment->service->name); ?>


                            
                            <?php $__currentLoopData = $appointment->extra_items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $extra): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <br><small class="extra-line">+ <?php echo e($extra->service->name); ?></small>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            <?php if(auth()->user()->isAdmin() && ($walkInReady ?? false) && ! $appointment->is_package && ! $appointment->archived_at && ($appointment->canChangeExtras() || $appointment->extra_items->isNotEmpty())): ?>
                                <?php
                                    $extrasData = [
                                        'title'    => $appointment->user->full_name . ' · ' . $appointment->service->name,
                                        'addUrl'   => route('admin.appointments.extras.add', $appointment),
                                        'canEdit'  => $appointment->canChangeExtras(),
                                        'main'     => ['name' => $appointment->service->name, 'price' => (float) $appointment->service->price],
                                        'extras'   => $appointment->extra_items->map(fn ($e) => [
                                            'name'      => $e->service->name,
                                            'price'     => (float) $e->price,
                                            'removeUrl' => route('admin.appointments.extras.remove', $e),
                                        ])->values(),
                                    ];
                                ?>
                                <br>
                                <button type="button" class="extras-button" data-extras="<?php echo e(json_encode($extrasData)); ?>">
                                    <?php echo e($appointment->canChangeExtras() ? '＋ Add service' : 'View services'); ?>

                                </button>
                            <?php endif; ?>

                            <?php if($appointment->is_package): ?>
                                <?php echo $__env->make('partials.package-sessions', ['appointment' => $appointment], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                            <?php endif; ?>
                        </td>

                        
                        <td class="price-cell">
                            <?php if($appointment->extra_items->isNotEmpty()): ?>
                                <strong>₱<?php echo e(number_format($appointment->total_price, 2)); ?></strong>
                                <br><small><?php echo e($appointment->extra_items->count() + 1); ?> services</small>
                            <?php else: ?>
                                <?php echo e($appointment->service->display_price); ?>

                            <?php endif; ?>
                        </td>

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
                        <td colspan="<?php echo e(auth()->user()->isAdmin() ? 8 : 7); ?>">
                            <?php echo e(($selectedDate || ($statusFilter ?? null)) ? 'No bookings match these filters.' : ($showArchived ? 'No archived appointments.' : 'No appointments found.')); ?>

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
    .new-alert {
        display:flex; align-items:center; gap:14px; flex-wrap:wrap;
        margin:0 0 16px; padding:14px 18px; border-radius:16px;
        background:rgba(229,56,59,.10); border:1px solid rgba(229,56,59,.45); color:var(--heading);
    }
    .new-alert.none { background:var(--surface-soft); border-color:var(--line); font-weight:600; }
    .new-alert-icon { font-size:1.4rem; }
    .new-alert-text { display:flex; flex-direction:column; flex:1 1 220px; }
    .new-alert-btn {
        padding:9px 16px; border-radius:999px; background:#e5383b; color:#fff;
        font-weight:700; text-decoration:none;
    }
    .new-pill {
        display:inline-block; margin-top:4px; padding:2px 9px; border-radius:999px;
        background:#e5383b; color:#fff; font-size:11px; font-weight:800; letter-spacing:.06em;
    }
</style>

<style>
    .price-cell { white-space:nowrap; font-weight:700; color:var(--heading); }
    .price-cell small { font-weight:500; color:var(--mauve); }
    .date-filter { display:flex; align-items:center; gap:10px; flex-wrap:wrap; margin-bottom:14px; }
    .date-filter label { font-weight:700; color:var(--heading); }
    .date-filter select.status-filter {
        padding:10px 12px; border:1px solid var(--line); border-radius:12px;
        background:var(--surface); color:var(--heading); font:inherit; width:auto; margin:0;
    }
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
    .print-link {
        display: block;
        margin-top: 6px;
        padding: 6px 10px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 700;
        text-align: center;
        text-decoration: none;
        color: #62444D;
        background: linear-gradient(135deg, #F3BCD2, #FFAFF1);
        white-space: nowrap;
    }
    .print-link:hover { filter: brightness(1.05); }

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


<?php if(auth()->user()->isAdmin() && ($walkInReady ?? false)): ?>


<div id="extras-modal" class="details-modal" hidden>
    <div class="details-backdrop" data-close-extras></div>

    <div class="details-card" role="dialog" aria-modal="true" aria-labelledby="extras-title">
        <button type="button" class="details-close" data-close-extras aria-label="Close">&times;</button>

        <span class="eyebrow">SAME VISIT</span>
        <h2 id="extras-title">Services in this visit</h2>
        <p class="muted" id="extras-sub"></p>

        <div class="details-group">
            <h3>Services</h3>
            <ul class="extras-list" id="extras-list"></ul>
            <div class="extras-total"><span>Total</span><strong id="extras-total"></strong></div>
        </div>

        <form method="POST" id="extras-add-form" class="details-group extras-add">
            <?php echo csrf_field(); ?>
            <h3>Add another service</h3>
            <select name="service_id" required>
                <option value="">-- Choose a service --</option>
                <?php $__currentLoopData = $extraServices->groupBy('category'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category => $items): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <optgroup label="<?php echo e($category ?: 'Other'); ?>">
                        <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($item->id); ?>"><?php echo e($item->name); ?> — ₱<?php echo e(number_format((float) $item->price, 0)); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </optgroup>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <button class="primary-button" type="submit">Add to this visit</button>
            <p class="muted">Same client, same date and time. It does not count as a new booking.</p>
        </form>

        <p class="muted" id="extras-locked" hidden>This visit is closed, so its services can no longer be changed.</p>

        
        <form method="POST" id="extras-remove-form" hidden>
            <?php echo csrf_field(); ?>
            <?php echo method_field('DELETE'); ?>
        </form>
    </div>
</div>



<div id="walkin-modal" class="details-modal" hidden>
    <div class="details-backdrop" data-close-walkin></div>

    <div class="details-card" role="dialog" aria-modal="true" aria-labelledby="walkin-title">
        <button type="button" class="details-close" data-close-walkin aria-label="Close">&times;</button>

        <span class="eyebrow">AT THE CLINIC</span>
        <h2 id="walkin-title">Add walk-in</h2>
        <p class="muted">For a customer who came in without booking online. Client level limits do not apply.</p>

        <form method="POST" action="<?php echo e(route('admin.appointments.walkin')); ?>" class="walkin-form">
            <?php echo csrf_field(); ?>

            <div class="walkin-modes">
                <label><input type="radio" name="client_mode" value="existing" <?php echo e(old('client_mode', 'existing') === 'existing' ? 'checked' : ''); ?>> Registered client</label>
                <label><input type="radio" name="client_mode" value="new" <?php echo e(old('client_mode') === 'new' ? 'checked' : ''); ?>> No account</label>
            </div>

            <div data-mode="existing">
                <label>Client
                    <select name="user_id">
                        <option value="">-- Choose a client --</option>
                        <?php $__currentLoopData = $walkInClients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($c->id); ?>" <?php if(old('user_id') == $c->id): echo 'selected'; endif; ?>><?php echo e(trim($c->first_name . ' ' . $c->last_name)); ?><?php echo e($c->phone ? ' · ' . $c->phone : ''); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </label>
            </div>

            <div data-mode="new" hidden>
                <div class="walkin-grid">
                    <label>First name <input type="text" name="first_name" value="<?php echo e(old('first_name')); ?>" maxlength="100"></label>
                    <label>Last name <input type="text" name="last_name" value="<?php echo e(old('last_name')); ?>" maxlength="100"></label>
                </div>
                <label>Phone (optional)
                    <input type="tel" name="phone" value="<?php echo e(old('phone')); ?>" maxlength="11" inputmode="numeric" placeholder="09XXXXXXXXX"
                           oninput="this.value = this.value.replace(/\D/g, '').slice(0, 11);">
                </label>
            </div>

            <label>Service
                <select name="service_id" required>
                    <option value="">-- Choose a service --</option>
                    <?php $__currentLoopData = \App\Models\Service::where('is_available', true)->orderBy('category')->orderBy('name')->get(['id', 'name', 'category', 'price'])->groupBy('category'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category => $items): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <optgroup label="<?php echo e($category ?: 'Other'); ?>">
                            <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($item->id); ?>" <?php if(old('service_id') == $item->id): echo 'selected'; endif; ?>><?php echo e($item->name); ?> — ₱<?php echo e(number_format((float) $item->price, 0)); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </optgroup>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </label>

            <label>Served by
                <select name="provider">
                    <option value="any">Any available</option>
                    <option value="owner" <?php if(old('provider') === 'owner'): echo 'selected'; endif; ?>>The Owner</option>
                    <?php $__currentLoopData = $staff; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($member->id); ?>" <?php if(old('provider') == $member->id): echo 'selected'; endif; ?>><?php echo e($member->user->full_name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </label>

            <div class="walkin-grid">
                <label>Date <input type="date" name="appointment_date" value="<?php echo e(old('appointment_date', now()->toDateString())); ?>" min="<?php echo e(now()->toDateString()); ?>" required></label>
                <label>Time <input type="time" name="appointment_time" value="<?php echo e(old('appointment_time', now()->format('H:i'))); ?>" required></label>
            </div>

            <label>Status
                <select name="status">
                    <option value="confirmed" <?php if(old('status', 'confirmed') === 'confirmed'): echo 'selected'; endif; ?>>Confirmed (customer is here)</option>
                    <option value="completed" <?php if(old('status') === 'completed'): echo 'selected'; endif; ?>>Completed (already done)</option>
                    <option value="pending"   <?php if(old('status') === 'pending'): echo 'selected'; endif; ?>>Pending</option>
                </select>
            </label>

            <label>Notes (optional)
                <textarea name="notes" rows="2" maxlength="1000"><?php echo e(old('notes')); ?></textarea>
            </label>

            <?php if($errors->has('walk_in') || $errors->hasAny(['user_id','first_name','last_name','phone','service_id','appointment_date','appointment_time'])): ?>
                <p class="walkin-error"><?php echo e($errors->first()); ?></p>
            <?php endif; ?>

            <button class="primary-button full" type="submit">Save walk-in</button>
        </form>
    </div>
</div>

<style>
    .extras-button {
        margin-top: 6px; padding: 5px 12px; border-radius: 999px; cursor: pointer; font: inherit; font-size: 12px; font-weight: 700;
        border: 1px solid var(--line); background: var(--surface-soft); color: var(--heading);
    }
    .extras-button:hover { background: var(--mauve); color: #fff; border-color: var(--mauve); }
    .extra-line { color: var(--mauve); font-weight: 700; }
    .extra-total { color: var(--heading); font-weight: 800; }
    .walkin-pill {
        display:inline-block; margin-top:4px; padding:2px 9px; border-radius:999px;
        background:#2f8f6b; color:#fff; font-size:11px; font-weight:800; letter-spacing:.06em;
    }
    .extras-list { list-style:none; margin:0 0 8px; padding:0; }
    .extras-list li { display:flex; justify-content:space-between; align-items:center; gap:10px; padding:8px 0; border-bottom:1px dashed var(--line); }
    .extras-list li span.price { margin-left:auto; font-weight:700; }
    .extras-remove { border:0; background:transparent; color:#d64b6b; font-size:20px; line-height:1; cursor:pointer; }
    .extras-total { display:flex; justify-content:space-between; font-size:1.05rem; }
    .extras-add select, .walkin-form select, .walkin-form input, .walkin-form textarea { width:100%; margin:4px 0 10px; }
    .walkin-form label { display:block; font-weight:600; }
    .walkin-grid { display:grid; grid-template-columns:1fr 1fr; gap:12px; }
    .walkin-modes { display:flex; gap:18px; margin-bottom:10px; }
    .walkin-modes input { width:auto; margin:0 6px 0 0; }
    .walkin-error { color:#d64b6b; margin:0 0 10px; }
    .walkin-form [hidden] { display:none; }
    #walkin-modal .details-card { max-height: 92vh; overflow-y: auto; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {

    var peso = function (n) { return '₱' + Number(n).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 }); };

    function bindModal(modal, closeAttr) {
        function close() { modal.hidden = true; document.body.style.overflow = ''; }
        function open()  { modal.hidden = false; document.body.style.overflow = 'hidden'; }
        modal.querySelectorAll('[' + closeAttr + ']').forEach(function (el) { el.addEventListener('click', close); });
        document.addEventListener('keydown', function (e) { if (e.key === 'Escape' && !modal.hidden) { close(); } });
        return { open: open, close: close };
    }

    // ----- add service to a visit -----
    var extrasModal = document.getElementById('extras-modal');
    var extras = bindModal(extrasModal, 'data-close-extras');

    document.querySelectorAll('.extras-button').forEach(function (button) {
        button.addEventListener('click', function () {
            var d = {};
            try { d = JSON.parse(button.dataset.extras); } catch (e) {}

            document.getElementById('extras-sub').textContent = d.title || '';
            var list = document.getElementById('extras-list');
            list.innerHTML = '';
            var total = Number(d.main.price);

            function row(name, price, removeUrl) {
                var li = document.createElement('li');
                var n = document.createElement('span'); n.textContent = name;
                var p = document.createElement('span'); p.className = 'price'; p.textContent = peso(price);
                li.appendChild(n); li.appendChild(p);
                if (removeUrl && d.canEdit) {
                    var x = document.createElement('button');
                    x.type = 'button'; x.className = 'extras-remove'; x.title = 'Remove'; x.innerHTML = '&times;';
                    x.addEventListener('click', function () {
                        if (!confirm('Remove ' + name + ' from this visit?')) { return; }
                        var f = document.getElementById('extras-remove-form');
                        f.action = removeUrl;
                        f.submit();
                    });
                    li.appendChild(x);
                }
                list.appendChild(li);
            }

            row(d.main.name, d.main.price, null);
            (d.extras || []).forEach(function (e) { row('+ ' + e.name, e.price, e.removeUrl); total += Number(e.price); });
            document.getElementById('extras-total').textContent = peso(total);

            var addForm = document.getElementById('extras-add-form');
            addForm.action = d.addUrl;
            addForm.hidden = !d.canEdit;
            document.getElementById('extras-locked').hidden = !!d.canEdit;

            extras.open();
        });
    });

    // ----- walk-in -----
    var walkinModal = document.getElementById('walkin-modal');
    var walkin = bindModal(walkinModal, 'data-close-walkin');
    var openBtn = document.getElementById('open-walkin');
    if (openBtn) { openBtn.addEventListener('click', walkin.open); }

    function syncMode() {
        var mode = walkinModal.querySelector('input[name="client_mode"]:checked').value;
        walkinModal.querySelectorAll('[data-mode]').forEach(function (box) { box.hidden = box.dataset.mode !== mode; });
    }
    walkinModal.querySelectorAll('input[name="client_mode"]').forEach(function (r) { r.addEventListener('change', syncMode); });
    syncMode();

    <?php if($errors->has('walk_in') || $errors->hasAny(['user_id','first_name','last_name','phone','service_id','appointment_date','appointment_time'])): ?>
        walkin.open();
    <?php endif; ?>
});
</script>

<?php endif; ?>

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