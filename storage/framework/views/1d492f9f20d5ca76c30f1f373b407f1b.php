

<?php $__env->startSection('title', 'Manage Promos | M. Cares'); ?>

<?php $__env->startSection('content'); ?>

<?php
    $liveCount = $promos->where('is_active', true)->count();
?>

<section class="page-hero">
    <div class="container">
        <span class="eyebrow">ADMIN · PROMOS</span>
        <h1>Manage promos</h1>
        <p>Add as many promos as you like. Every live promo slides automatically on the home page and in the top announcement bar.</p>
    </div>
</section>

<section class="section compact">

    <div class="container admin-two-col">

        
        <div class="panel">

            <span class="eyebrow">ADD PROMO</span>
            <h2>New promo</h2>

            <form method="POST"
                  action="<?php echo e(route('admin.promo.store')); ?>"
                  class="form-stack"
                  enctype="multipart/form-data">

                <?php echo csrf_field(); ?>

                <label>Promo title <small>(shown under the poster)</small>
                    <input name="title" value="<?php echo e(old('title')); ?>" maxlength="150" required>
                </label>

                <label>Announcement bar text <small>(short line at the very top of every page; leave empty to reuse the title)</small>
                    <input name="bar_text" value="<?php echo e(old('bar_text')); ?>" maxlength="150">
                </label>

                <div class="svc-photo-field">

                    <span class="svc-photo-label">Promo poster</span>

                    <div class="svc-photo-preview">
                        <span>No poster selected</span>
                    </div>

                    <input type="file"
                           name="image"
                           accept="image/jpeg,image/png,image/webp"
                           class="svc-file-input promo-image-input"
                           required>

                    <small>JPG, PNG or WEBP · up to 5 MB · a wide poster (16:9 or similar) fits the slideshow best</small>

                </div>

                <label class="promo-check">
                    <input type="checkbox" name="is_active" value="1" <?php if(old('title') === null || old('is_active')): echo 'checked'; endif; ?>>
                    Show this promo on the website
                </label>

                <button class="primary-button full">Add promo</button>

            </form>

        </div>


        
        <div class="panel">

            <span class="eyebrow">PROMO LIST</span>
            <h2>Current promos</h2>

            <?php if($liveCount > 0): ?>
                <p class="promo-status on">&#9679; <?php echo e($liveCount); ?> <?php echo e($liveCount === 1 ? 'promo is' : 'promos are'); ?> live on the website</p>
            <?php else: ?>
                <p class="promo-status off">&#9679; Nothing is live. Clients do not see a promo right now</p>
            <?php endif; ?>

            <div class="admin-list">

                <?php $__empty_1 = true; $__currentLoopData = $promos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $promo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                    <div class="admin-item svc-item">

                        <div class="svc-summary">

                            <img class="svc-thumb promo-thumb" src="<?php echo e($promo->image_url); ?>" alt="<?php echo e($promo->title); ?>" loading="lazy">

                            <div>
                                <strong><?php echo e($promo->title); ?></strong>
                                <p><?php echo e($promo->is_active ? 'Live on the website' : 'Hidden'); ?></p>
                                <small>Bar: <?php echo e($promo->bar_line); ?></small>
                            </div>

                        </div>

                        <details class="svc-details">

                            <summary>Edit</summary>

                            <form method="POST"
                                  action="<?php echo e(route('admin.promo.update', $promo)); ?>"
                                  class="form-stack mini"
                                  enctype="multipart/form-data">

                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PUT'); ?>

                                <label>Promo title
                                    <input name="title" value="<?php echo e($promo->title); ?>" maxlength="150" required>
                                </label>

                                <label>Announcement bar text <small>(empty = use the title)</small>
                                    <input name="bar_text" value="<?php echo e($promo->bar_text); ?>" maxlength="150">
                                </label>

                                <div class="svc-photo-field">

                                    <span class="svc-photo-label">Poster</span>

                                    <div class="svc-photo-preview">
                                        <img src="<?php echo e($promo->image_url); ?>" alt="Current poster">
                                    </div>

                                    <input type="file"
                                           name="image"
                                           accept="image/jpeg,image/png,image/webp"
                                           class="svc-file-input promo-image-input">

                                    <small>Choose a new file only if you want to replace the poster.</small>

                                    <?php if($promo->image): ?>
                                        <label class="promo-check">
                                            <input type="checkbox" name="remove_image" value="1">
                                            Go back to the original poster
                                        </label>
                                    <?php endif; ?>

                                </div>

                                <label class="promo-check">
                                    <input type="checkbox" name="is_active" value="1" <?php if($promo->is_active): echo 'checked'; endif; ?>>
                                    Show on the website (untick to hide it)
                                </label>

                                <button class="small-button">Save</button>

                            </form>

                            <form method="POST" action="<?php echo e(route('admin.promo.destroy', $promo)); ?>">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button class="danger-link"
                                        onclick="return confirm('Delete this promo? This cannot be undone.')">
                                    Delete
                                </button>
                            </form>

                        </details>

                    </div>

                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

                    <p>No promos yet. Add your first one on the left.</p>

                <?php endif; ?>

            </div>

        </div>

    </div>

</section>



<section class="section compact">

    <div class="container">

        <div class="panel">

            <span class="eyebrow">PREVIEW</span>
            <h2>What clients see</h2>

            <?php $__empty_1 = true; $__currentLoopData = $promos->where('is_active', true); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $livePromo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="promo-bar-preview">
                    &#127872; <strong>PROMO:</strong> <?php echo e($livePromo->bar_line); ?> &mdash; Book now &rarr;
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p class="promo-status off">No live promos, so no announcement bar and no slideshow.</p>
            <?php endif; ?>

            <?php echo $__env->make('partials.promo-banner', ['compact' => true, 'admin' => true], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        </div>

    </div>

</section>


<style>
    .promo-check { display: flex; align-items: center; gap: 10px; font-weight: 600; }
    .promo-check input { width: auto; }
    .promo-status { font-weight: 700; margin: 0 0 14px; }
    .promo-status.on { color: #2e8b57; }
    .promo-status.off { color: #b4534f; }
    .promo-bar-preview {
        margin-bottom: 10px;
        padding: 10px 16px;
        border-radius: 12px;
        background: linear-gradient(110deg, #70475e, #b46e91, #70475e);
        color: #fff;
        text-align: center;
        font-size: 13px;
        font-weight: 600;
    }
    .promo-bar-preview strong { color: #ffe9a8; }
    .promo-bar-preview + .promo-section { margin-top: 18px; }

    .svc-item { align-items: flex-start; }
    .svc-summary { display: flex; gap: 14px; align-items: center; }
    .svc-thumb {
        flex: none;
        width: 64px;
        height: 64px;
        object-fit: cover;
        border-radius: 14px;
        border: 1px solid var(--line, #ddd);
    }
    .promo-thumb { width: 96px; height: 54px; object-fit: contain; background: var(--surface-soft, #fff1f7); }
    .svc-details { flex: 1 1 340px; }

    .svc-photo-field { display: grid; gap: 8px; }
    .svc-photo-label { font-weight: 700; color: var(--heading, #62444D); }
    .svc-photo-field small { color: var(--text-muted, #7d7074); }
    .svc-photo-preview {
        display: grid; place-items: center; min-height: 120px; overflow: hidden;
        border-radius: 18px; border: 2px dashed var(--line, #ccc);
        background: var(--surface-soft, #fff1f7);
        color: var(--text-muted, #7d7074);
    }
    .svc-photo-preview img { width: 100%; max-height: 240px; object-fit: contain; display: block; }
    .svc-file-input {
        padding: 10px; border-radius: 14px; width: 100%;
        border: 1px solid var(--input-border, #ded5d9);
        background: var(--input-bg, #fff); color: var(--text, #222);
    }
</style>

<script>
    // shows the chosen poster before saving (works for every promo form)
    document.querySelectorAll('.promo-image-input').forEach(function (input) {

        input.addEventListener('change', function () {

            const box = input.closest('.svc-photo-field').querySelector('.svc-photo-preview');
            const file = input.files && input.files[0];

            if (!box || !file) { return; }

            const img = document.createElement('img');
            img.src = URL.createObjectURL(file);
            img.alt = 'Selected poster';
            box.innerHTML = '';
            box.appendChild(img);
        });

    });
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Regis D\Desktop\Beauty-Aesthetic-Appointment-System\resources\views/admin/promo.blade.php ENDPATH**/ ?>