<?php
    $compact = $compact ?? false;
    $admin   = $admin   ?? false;

    $promo = \App\Models\Promo::current();

    $promoLink = auth()->check()
        ? (auth()->user()->isClient()
            ? route('client.appointments.create')
            : route('home'))
        : route('register');
?>

<?php if($promo->is_active): ?>
<section class="promo-section <?php echo e($compact ? 'is-compact' : ''); ?>">

    <?php if (! ($compact)): ?><div class="container"><?php endif; ?>

        <div class="promo-card">

            <a class="promo-poster" href="<?php echo e($admin ? route('home') : $promoLink); ?>">

                <img
                    src="<?php echo e($promo->image_url); ?>"
                    alt="<?php echo e($promo->title); ?>"
                    loading="lazy"
                >

            </a>

            <div class="promo-caption">

                <div>

                    <span class="eyebrow">
                        <?php echo e($admin ? 'ACTIVE PROMOTION' : 'LIMITED-TIME PROMO'); ?>

                    </span>

                    <h3><?php echo e($promo->title); ?></h3>

                </div>

                <?php if($admin): ?>

                    <span class="promo-live">
                        &#9679; Showing on the website and client pages
                    </span>

                <?php else: ?>

                    <a class="promo-cta" href="<?php echo e($promoLink); ?>">
                        Book this promo
                    </a>

                <?php endif; ?>

            </div>

        </div>

    <?php if (! ($compact)): ?></div><?php endif; ?>

</section>
<?php endif; ?>
<?php /**PATH C:\Users\Regis D\Desktop\Beauty-Aesthetic-Appointment-System\resources\views/partials/promo-banner.blade.php ENDPATH**/ ?>