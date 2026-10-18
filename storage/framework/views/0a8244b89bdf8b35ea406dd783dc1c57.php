

<div id="sa-modal" class="sa-modal" hidden>
    <div class="sa-backdrop" data-sa-close></div>

    <div class="sa-card" role="dialog" aria-modal="true" aria-labelledby="sa-title">

        <button type="button" class="sa-close" data-sa-close aria-label="Close">&times;</button>

        <span class="eyebrow">APPOINTMENT</span>
        <h2 id="sa-title">Request details</h2>

        <div class="sa-group">
            <h3>Client</h3>
            <dl>
                <dt>Name</dt><dd data-f="client"></dd>
                <dt>Phone</dt><dd data-f="phone"></dd>
                <dt>Email</dt><dd data-f="email"></dd>
                <dt>Level</dt><dd data-f="level"></dd>
            </dl>
        </div>

        <div class="sa-group">
            <h3>Service</h3>
            <dl>
                <dt>Service</dt><dd data-f="service"></dd>
                <dt>Type</dt><dd data-f="category"></dd>
                <dt>Price</dt><dd data-f="price"></dd>
                <dt>Duration</dt><dd data-f="duration"></dd>
            </dl>
        </div>

        <div class="sa-group">
            <h3>Schedule</h3>
            <dl>
                <dt>Date</dt><dd data-f="dateLabel"></dd>
                <dt>Time</dt><dd data-f="timeLabel"></dd>
                <dt>Serve by</dt><dd data-f="provider"></dd>
                <dt>Status</dt><dd data-f="status"></dd>
                <dt>Booked on</dt><dd data-f="booked"></dd>
            </dl>
        </div>

        <div class="sa-group">
            <h3>Client notes</h3>
            <p class="sa-notes" data-f="notes"></p>
        </div>

        
        <form method="POST" id="sa-manage" class="sa-group sa-form" hidden>
            <?php echo csrf_field(); ?>
            <?php echo method_field('PATCH'); ?>

            <h3>Update this appointment</h3>

            <div class="sa-fields">
                <label>Status
                    <select name="status" id="sa-status"></select>
                </label>

                <label>Date
                    <input type="date" name="appointment_date" id="sa-date" min="<?php echo e(now()->format('Y-m-d')); ?>" required>
                </label>

                <label>Time
                    <input type="time" name="appointment_time" id="sa-time" required>
                </label>
            </div>

            <p class="sa-help">Changing the date or time marks the appointment as rescheduled. You can only complete an appointment on or after its day.</p>

            <button class="primary-button">Save changes</button>
        </form>

        <form method="POST" id="sa-claim" class="sa-group sa-form" hidden>
            <?php echo csrf_field(); ?>
            <?php echo method_field('PATCH'); ?>
            <h3>Not assigned yet</h3>
            <p class="sa-help">The client has no preference. Take it to manage it.</p>
            <button class="primary-button">Take this appointment</button>
        </form>

        <p id="sa-readonly" class="sa-help sa-group" hidden></p>

    </div>
</div>

<style>
    .sa-actions { display: flex; flex-wrap: wrap; gap: 8px; align-items: center; }

    .sa-open, .sa-claim {
        padding: 8px 14px;
        border-radius: 999px;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
    }
    .sa-open  { border: 1px solid var(--line); background: var(--surface-soft); color: var(--heading); }
    .sa-open:hover { background: var(--mauve); border-color: var(--mauve); color: #fff; }
    .sa-claim { border: 0; background: var(--mauve); color: #fff; }
    .sa-claim:hover { background: #8b5e6e; }

    .sa-modal { position: fixed; inset: 0; z-index: 200; display: flex; align-items: center; justify-content: center; padding: 16px; }
    .sa-modal[hidden] { display: none; }
    .sa-backdrop { position: absolute; inset: 0; background: rgba(20, 10, 16, .6); }

    .sa-card {
        position: relative;
        width: min(580px, 100%);
        max-height: calc(100vh - 32px);
        overflow-y: auto;
        padding: 26px 24px 24px;
        border-radius: 24px;
        background: var(--surface);
        border: 1px solid var(--line);
        box-shadow: 0 24px 60px rgba(0, 0, 0, .35);
    }
    .sa-card h2 { margin: 4px 0 14px; color: var(--heading); }
    .sa-card h3 { margin: 0 0 6px; font-size: 12px; letter-spacing: 1.5px; text-transform: uppercase; color: var(--mauve); }

    .sa-close {
        position: absolute; top: 12px; right: 14px;
        width: 40px; height: 40px;
        border: 0; border-radius: 50%;
        background: var(--surface-soft); color: var(--heading);
        font-size: 24px; line-height: 1; cursor: pointer;
    }

    .sa-group { padding: 12px 0; border-top: 1px solid var(--line); }
    .sa-group[hidden] { display: none; }
    .sa-group dl { display: grid; grid-template-columns: 100px minmax(0, 1fr); gap: 6px 12px; margin: 0; }
    .sa-group dt { color: var(--text-muted, #7d7074); font-size: 13px; }
    .sa-group dd { margin: 0; font-weight: 600; word-break: break-word; }
    .sa-notes { margin: 0; white-space: pre-wrap; word-break: break-word; }
    .sa-notes.is-empty { color: var(--text-muted, #7d7074); font-style: italic; }
    .sa-help { margin: 8px 0 12px; font-size: 13px; color: var(--text-muted, #7d7074); }

    .sa-fields { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 12px; }
    .sa-fields label { display: grid; gap: 6px; font-weight: 600; font-size: 13px; }
    @media (max-width: 560px) { .sa-fields { grid-template-columns: 1fr; } }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {

    var modal    = document.getElementById('sa-modal');
    var manage   = document.getElementById('sa-manage');
    var claim    = document.getElementById('sa-claim');
    var readonly = document.getElementById('sa-readonly');
    var select   = document.getElementById('sa-status');

    function close() { modal.hidden = true; document.body.style.overflow = ''; }

    document.querySelectorAll('.sa-open').forEach(function (button) {
        button.addEventListener('click', function () {

            var d = {};
            try { d = JSON.parse(button.dataset.appointment); } catch (e) { return; }

            modal.querySelectorAll('[data-f]').forEach(function (el) {
                var value = d[el.dataset.f];

                if (el.dataset.f === 'notes') {
                    el.textContent = value ? value : 'The client did not add any notes.';
                    el.classList.toggle('is-empty', !value);
                } else {
                    el.textContent = value || '-';
                }
            });

            manage.hidden = !d.canEdit;
            claim.hidden = !d.canClaim;
            readonly.hidden = true;

            if (d.canEdit) {
                manage.action = d.updateUrl;
                select.innerHTML = '';
                d.statuses.forEach(function (s) {
                    var option = document.createElement('option');
                    option.value = s.value;
                    option.textContent = s.label;
                    select.appendChild(option);
                });
                select.value = d.statuses[0].value;
                document.getElementById('sa-date').value = d.date;
                document.getElementById('sa-time').value = d.time;
            }

            if (d.canClaim) { claim.action = d.claimUrl; }

            if (!d.canEdit && !d.canClaim) {
                readonly.hidden = false;
                readonly.textContent = d.locked
                    ? 'This appointment is finished. Only the admin can change it.'
                    : 'This appointment is handled by the owner or another staff member, so you can only view it.';
            }

            modal.hidden = false;
            document.body.style.overflow = 'hidden';
            modal.querySelector('.sa-close').focus();
        });
    });

    modal.querySelectorAll('[data-sa-close]').forEach(function (el) { el.addEventListener('click', close); });
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape' && !modal.hidden) { close(); } });
});
</script>
<?php /**PATH C:\Users\Regis D\Desktop\Beauty-Aesthetic-Appointment-System\resources\views/staff/partials/appointment-modal.blade.php ENDPATH**/ ?>