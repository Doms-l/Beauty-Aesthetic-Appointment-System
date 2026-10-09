
<?php
    $selectedProvider = old('provider', 'any');
?>

<div class="provider-picker">

    <div class="provider-heading">
        <span class="provider-number">03</span>
        <div>
            <h2>Choose who serves you</h2>
            <p>Pick the owner or one of our staff, or let the clinic assign anyone available.</p>
        </div>
    </div>

    <div class="provider-grid">

        <label class="provider-card">
            <input type="radio" name="provider" value="any" <?php if($selectedProvider === 'any'): echo 'checked'; endif; ?>>
            <span class="provider-body">
                <span class="provider-avatar">✨</span>
                <strong>No preference</strong>
                <small>Anyone available</small>
            </span>
        </label>

        <label class="provider-card">
            <input type="radio" name="provider" value="owner" <?php if($selectedProvider === 'owner'): echo 'checked'; endif; ?>>
            <span class="provider-body">
                <span class="provider-avatar">👑</span>
                <strong>The Owner</strong>
                <small>Served personally by the owner</small>
            </span>
        </label>

        <?php $__currentLoopData = $staffMembers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <label class="provider-card">
                <input type="radio" name="provider" value="<?php echo e($member->id); ?>" <?php if((string) $selectedProvider === (string) $member->id): echo 'checked'; endif; ?>>
                <span class="provider-body">
                    <span class="provider-avatar">
                        <?php echo e(strtoupper(mb_substr($member->user->first_name ?? $member->user->full_name, 0, 1))); ?>

                    </span>
                    <strong><?php echo e($member->user->full_name); ?></strong>
                    <small><?php echo e($member->position); ?><?php echo e($member->specialization ? ' · ' . $member->specialization : ''); ?></small>
                </span>
            </label>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    </div>

    <?php $__errorArgs = ['provider'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <p class="provider-error"><?php echo e($message); ?></p>
    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

</div>

<style>
    .provider-picker { margin: 8px 0 26px; }

    .provider-heading { display: flex; gap: 14px; align-items: flex-start; margin-bottom: 16px; }
    .provider-number { color: #D6B36A; font-weight: 700; padding-top: 6px; }
    .provider-heading h2 { margin: 0; color: var(--heading, #62444D); }
    .provider-heading p { margin: 6px 0 0; color: var(--text, #222); }

    .provider-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(190px, 1fr));
        gap: 14px;
    }
    .provider-card { display: block; cursor: pointer; position: relative; }
    .provider-card input {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }
    .provider-body {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 6px;
        height: 100%;
        padding: 18px 14px;
        text-align: center;
        border-radius: 20px;
        background: var(--surface, #fff);
        border: 2px solid var(--line, #ddd);
        transition: border-color .2s, transform .2s, box-shadow .2s;
    }
    .provider-body strong { color: var(--heading, #62444D); }
    .provider-body small { color: var(--text-muted, #7d7074); }

    .provider-avatar {
        width: 54px;
        height: 54px;
        display: grid;
        place-items: center;
        font-size: 1.4rem;
        font-weight: 700;
        color: #62444D;
        border-radius: 50%;
        background: linear-gradient(135deg, #F3BCD2, #FFAFF1);
    }
    .provider-card:hover .provider-body { transform: translateY(-2px); }
    .provider-card input:checked + .provider-body {
        border-color: #E8AECF;
        box-shadow: 0 0 0 3px rgba(255, 175, 241, .35);
        background: var(--surface-soft, #fff1f7);
    }
    .provider-card input:focus-visible + .provider-body { outline: 2px solid #62444D; }

    .provider-error { color: #d64b6b; margin-top: 10px; }
</style>
<?php /**PATH C:\Users\Regis D\Desktop\Beauty-Aesthetic-Appointment-System\resources\views/partials/provider-picker.blade.php ENDPATH**/ ?>