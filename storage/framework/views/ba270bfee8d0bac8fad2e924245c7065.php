

<?php $__env->startSection('title', 'Social Accounts | M. Cares'); ?>

<?php $__env->startSection('content'); ?>

<section class="page-hero">
    <div class="container">
        <span class="eyebrow">ADMIN · SOCIAL ACCOUNTS</span>
        <h1>Manage social accounts</h1>
        <p>These show in the website footer. Paste a link, email or phone number and the logo is picked for you automatically.</p>
    </div>
</section>

<section class="section compact">

    <div class="container admin-two-col">

        
        <div class="panel">

            <span class="eyebrow">ADD ACCOUNT</span>
            <h2>New social account</h2>

            <form method="POST"
                  action="<?php echo e(route('admin.socials.store')); ?>"
                  class="form-stack"
                  data-social-form>

                <?php echo csrf_field(); ?>

                <label>Link, email or phone number
                    <input name="url"
                           value="<?php echo e(old('url')); ?>"
                           maxlength="255"
                           placeholder="https://www.instagram.com/yourpage"
                           data-social-url
                           required>
                </label>

                <label>Name shown in the footer
                    <input name="label"
                           value="<?php echo e(old('label')); ?>"
                           maxlength="100"
                           placeholder="e.g. @macayla.cares"
                           data-social-label
                           required>
                </label>

                <label>Platform / logo
                    <select name="platform" data-social-platform>
                        <option value="auto" <?php if(old('platform', 'auto') === 'auto'): echo 'selected'; endif; ?>>Auto-detect from the link (recommended)</option>
                        <?php $__currentLoopData = $platforms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $platform): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($key); ?>" <?php if(old('platform') === $key): echo 'selected'; endif; ?>><?php echo e($platform['name']); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </label>

                
                <div class="social-preview">
                    <span class="social-preview-title">Preview</span>
                    <div class="social-preview-chip">
                        <span class="footer-icon" data-social-icon></span>
                        <span data-social-text>Your account name</span>
                    </div>
                    <small data-social-detected>Type a link to see its logo.</small>
                </div>

                <label>Order <small>(1 = first in the footer; leave empty to add at the end)</small>
                    <input type="number" name="sort_order" min="0" max="9999" value="<?php echo e(old('sort_order')); ?>">
                </label>

                <label class="promo-check">
                    <input type="checkbox" name="is_active" value="1" <?php if(old('url') === null || old('is_active')): echo 'checked'; endif; ?>>
                    Show in the footer
                </label>

                <button class="primary-button full">Add social account</button>

            </form>

        </div>


        
        <div class="panel">

            <span class="eyebrow">ACCOUNT LIST</span>
            <h2>Current social accounts</h2>

            <div class="admin-list">

                <?php $__empty_1 = true; $__currentLoopData = $links; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $link): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                    <div class="admin-item svc-item">

                        <div class="svc-summary">

                            <span class="footer-icon social-list-icon">
                                <?php echo $__env->make('partials.social-icon', ['link' => $link], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                            </span>

                            <div>
                                <strong><?php echo e($link->label); ?></strong>
                                <p><?php echo e($link->platform_name); ?> · <?php echo e($link->is_active ? 'Shown in the footer' : 'Hidden'); ?></p>
                                <small><?php echo e($link->url); ?></small>
                            </div>

                        </div>

                        <details class="svc-details">

                            <summary>Edit</summary>

                            <form method="POST"
                                  action="<?php echo e(route('admin.socials.update', $link)); ?>"
                                  class="form-stack mini"
                                  data-social-form>

                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PUT'); ?>

                                <label>Link, email or phone number
                                    <input name="url" value="<?php echo e($link->url); ?>" maxlength="255" data-social-url required>
                                </label>

                                <label>Name shown in the footer
                                    <input name="label" value="<?php echo e($link->label); ?>" maxlength="100" data-social-label required>
                                </label>

                                <label>Platform / logo
                                    <select name="platform" data-social-platform>
                                        <?php
                                            // keep "Auto-detect" selected while the saved logo is the one auto-detect would pick
                                            $isAuto = \App\Support\SocialPlatforms::detect($link->url) === $link->platform;
                                        ?>
                                        <option value="auto" <?php if($isAuto): echo 'selected'; endif; ?>>Auto-detect from the link</option>
                                        <?php $__currentLoopData = $platforms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $platform): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($key); ?>" <?php if(!$isAuto && $link->platform === $key): echo 'selected'; endif; ?>><?php echo e($platform['name']); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </label>

                                <div class="social-preview">
                                    <span class="social-preview-title">Preview</span>
                                    <div class="social-preview-chip">
                                        <span class="footer-icon" data-social-icon></span>
                                        <span data-social-text></span>
                                    </div>
                                    <small data-social-detected></small>
                                </div>

                                <label>Order
                                    <input type="number" name="sort_order" min="0" max="9999" value="<?php echo e($link->sort_order); ?>">
                                </label>

                                <label class="promo-check">
                                    <input type="checkbox" name="is_active" value="1" <?php if($link->is_active): echo 'checked'; endif; ?>>
                                    Show in the footer
                                </label>

                                <button class="small-button">Save</button>

                            </form>

                            <form method="POST" action="<?php echo e(route('admin.socials.destroy', $link)); ?>">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button class="danger-link"
                                        onclick="return confirm('Delete this social account? This cannot be undone.')">
                                    Delete
                                </button>
                            </form>

                        </details>

                    </div>

                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

                    <p>No social accounts yet. Add your first one on the left.</p>

                <?php endif; ?>

            </div>

        </div>

    </div>

</section>


<style>
    .promo-check { display: flex; align-items: center; gap: 10px; font-weight: 600; }
    .promo-check input { width: auto; }

    .svc-item { align-items: flex-start; }
    .svc-summary { display: flex; gap: 14px; align-items: center; }
    .svc-details { flex: 1 1 340px; }
    .svc-summary small { word-break: break-all; }

    /* the pink round logo, same as in the footer */
    .social-list-icon, .social-preview .footer-icon {
        flex: none;
        width: 44px;
        height: 44px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: #e91e8c;
        color: #ffffff;
        font-weight: bold;
        font-size: 20px;
    }
    .social-list-icon svg, .social-preview .footer-icon svg { width: 22px; height: 22px; }

    .social-preview {
        display: grid;
        gap: 8px;
        padding: 14px;
        border-radius: 16px;
        background: #111111;
        color: #ffffff;
    }
    .social-preview-title { font-size: .72rem; letter-spacing: .14em; text-transform: uppercase; color: #f3d6e5; }
    .social-preview-chip { display: flex; align-items: center; gap: 12px; font-size: 15px; word-break: break-word; }
    .social-preview small { color: #e8c9d9; }
</style>

<script>
(function () {

    // every platform: key => { name, type, domains, path }  (from the server)
    const PLATFORMS = <?php echo json_encode($platforms, 15, 512) ?>;

    function hostOf(value) {
        let v = value.trim();
        if (!/^[a-z][a-z0-9+.\-]*:\/\//i.test(v)) { v = 'https://' + v.replace(/^\/+/, ''); }
        try { return new URL(v).hostname.toLowerCase().replace(/^www\./, ''); } catch (e) { return ''; }
    }

    // same rules as the server, so the preview matches what gets saved
    function detect(value) {
        const v = value.trim();
        if (v === '') { return 'website'; }
        if (v.indexOf('/') === -1 && /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v)) { return 'email'; }
        if (/^\+?[0-9][0-9\s\-().]{5,24}$/.test(v)) { return 'phone'; }

        const host = hostOf(v);
        if (host) {
            for (const key in PLATFORMS) {
                for (const d of PLATFORMS[key].domains) {
                    if (host === d || host.endsWith('.' + d)) { return key; }
                }
            }
        }
        return 'website';
    }

    document.querySelectorAll('[data-social-form]').forEach(function (form) {

        const url      = form.querySelector('[data-social-url]');
        const label    = form.querySelector('[data-social-label]');
        const select   = form.querySelector('[data-social-platform]');
        const icon     = form.querySelector('[data-social-icon]');
        const text     = form.querySelector('[data-social-text]');
        const detected = form.querySelector('[data-social-detected]');

        function refresh() {

            const auto = select.value === 'auto';
            const key  = auto ? detect(url.value) : select.value;
            const p    = PLATFORMS[key];

            icon.innerHTML = p
                ? '<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="' + p.path + '"></path></svg>'
                : '';

            text.textContent = label.value.trim() || url.value.trim() || 'Your account name';

            detected.textContent = !p ? '' :
                (auto
                    ? (url.value.trim() ? 'Logo detected: ' + p.name : 'Type a link to see its logo.')
                    : 'Logo chosen: ' + p.name);

            // helpful placeholder for the chosen type
            if (p && p.type === 'phone') { url.placeholder = '09155168312'; }
            else if (p && p.type === 'email') { url.placeholder = 'hello@yourclinic.com'; }
        }

        [url, label, select].forEach(function (el) {
            el.addEventListener('input', refresh);
            el.addEventListener('change', refresh);
        });

        refresh();
    });

})();
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Regis D\Desktop\Beauty-Aesthetic-Appointment-System\resources\views/admin/socials.blade.php ENDPATH**/ ?>