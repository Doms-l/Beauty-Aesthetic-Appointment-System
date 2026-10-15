

<?php
    $compact = $compact ?? false;
    $admin   = $admin   ?? false;

    $promos = \App\Models\Promo::active();
    $total  = $promos->count();

?>

<?php if($total > 0): ?>
<section class="promo-section <?php echo e($compact ? 'is-compact' : ''); ?>">

    <?php if (! ($compact)): ?><div class="container"><?php endif; ?>

        <div class="promo-card promo-slideshow"
             data-promo-slideshow
             aria-roledescription="carousel"
             aria-label="Current promos">

            
            <div class="promo-stage">

                <?php $__currentLoopData = $promos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $promo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                    <a class="promo-poster promo-slide <?php echo e($i === 0 ? 'is-active' : ''); ?>"
                       href="<?php echo e($admin ? route('home') : $promo->bookingUrl()); ?>"
                       role="group"
                       aria-roledescription="slide"
                       aria-label="<?php echo e($i + 1); ?> of <?php echo e($total); ?>">

                        <span class="promo-poster-bg"
                              style="background-image: url('<?php echo e($promo->image_url); ?>');"></span>

                        <img src="<?php echo e($promo->image_url); ?>"
                             alt="<?php echo e($promo->title); ?>"
                             <?php if($i > 0): ?> loading="lazy" <?php endif; ?>>

                    </a>

                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                <?php if($total > 1): ?>

                    <button type="button" class="promo-nav promo-prev" aria-label="Previous promo">&#8249;</button>
                    <button type="button" class="promo-nav promo-next" aria-label="Next promo">&#8250;</button>

                    <div class="promo-dots">
                        <?php $__currentLoopData = $promos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $promo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <button type="button"
                                    class="promo-dot <?php echo e($i === 0 ? 'is-active' : ''); ?>"
                                    aria-label="Show promo <?php echo e($i + 1); ?>"></button>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>

                <?php endif; ?>

            </div>

            
            <div class="promo-captions">

                <?php $__currentLoopData = $promos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $promo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                    <div class="promo-caption promo-caption-slide <?php echo e($i === 0 ? 'is-active' : ''); ?>">

                        <div>

                            <span class="eyebrow">
                                <?php echo e($admin ? 'ACTIVE PROMOTION' : 'LIMITED-TIME PROMO'); ?>

                            </span>

                            <h3><?php echo e($promo->title); ?></h3>

                        </div>

                        <?php if($admin): ?>

                            <span class="promo-live">
                                &#9679; Showing on the website
                            </span>

                        <?php else: ?>

                            <a class="promo-cta" href="<?php echo e($promo->bookingUrl()); ?>">
                                Book this promo
                            </a>

                        <?php endif; ?>

                    </div>

                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            </div>

        </div>

    <?php if (! ($compact)): ?></div><?php endif; ?>

</section>


<style>

/* poster area: one fixed shape, so promos of any size fit */

.promo-slideshow .promo-stage {

    position: relative;

    aspect-ratio: 16 / 9;

    overflow: hidden;

    background: #f6e3ea;

}

.promo-slideshow .promo-slide {

    position: absolute;

    inset: 0;

    display: block;

    opacity: 0;

    visibility: hidden;

    transition: opacity .8s ease, visibility .8s;

}

.promo-slideshow .promo-slide.is-active {

    opacity: 1;

    visibility: visible;

    z-index: 1;

}

/* blurred copy of the poster fills any empty sides */

.promo-slideshow .promo-poster-bg {

    position: absolute;

    inset: -24px;

    background-position: center;

    background-size: cover;

    filter: blur(22px) brightness(.95);

}

.promo-slideshow .promo-slide img {

    position: relative;

    display: block;

    width: 100%;

    height: 100%;

    object-fit: contain;

}

/* captions sit on top of each other so the card never jumps in height */

.promo-slideshow .promo-captions {

    display: grid;

}

.promo-slideshow .promo-caption-slide {

    grid-area: 1 / 1;

    opacity: 0;

    visibility: hidden;

    transition: opacity .8s ease, visibility .8s;

}

.promo-slideshow .promo-caption-slide.is-active {

    opacity: 1;

    visibility: visible;

}

/* arrows */

.promo-slideshow .promo-nav {

    position: absolute;

    top: 50%;

    z-index: 3;

    width: 42px;

    height: 42px;

    margin-top: -21px;

    padding: 0 0 4px;

    border: 0;

    border-radius: 50%;

    background: rgba(40, 24, 32, .55);

    color: #ffffff;

    font-size: 28px;

    line-height: 1;

    cursor: pointer;

    opacity: 0;

    transition: opacity .25s ease, background .25s ease;

}

.promo-slideshow .promo-prev { left: 14px; }

.promo-slideshow .promo-next { right: 14px; }

.promo-slideshow:hover .promo-nav,
.promo-slideshow:focus-within .promo-nav {

    opacity: 1;

}

.promo-slideshow .promo-nav:hover {

    background: rgba(40, 24, 32, .8);

}

/* dots */

.promo-slideshow .promo-dots {

    position: absolute;

    left: 0;

    right: 0;

    bottom: 12px;

    z-index: 3;

    display: flex;

    justify-content: center;

    gap: 8px;

}

.promo-slideshow .promo-dot {

    width: 10px;

    height: 10px;

    padding: 0;

    border: 0;

    border-radius: 50%;

    background: rgba(255, 255, 255, .65);

    box-shadow: 0 0 0 1px rgba(40, 24, 32, .25);

    cursor: pointer;

    transition: background .25s ease, transform .25s ease;

}

.promo-slideshow .promo-dot.is-active {

    background: #e91e8c;

    transform: scale(1.25);

}

/* phones and touch screens: no hover, so keep the arrows visible */

@media (hover: none) {

    .promo-slideshow .promo-nav { opacity: 1; }

}

@media (max-width: 600px) {

    .promo-slideshow .promo-nav {

        width: 34px;

        height: 34px;

        margin-top: -17px;

        font-size: 24px;

    }

    .promo-slideshow .promo-prev { left: 8px; }

    .promo-slideshow .promo-next { right: 8px; }

}

@media (prefers-reduced-motion: reduce) {

    .promo-slideshow .promo-slide,
    .promo-slideshow .promo-caption-slide { transition: none; }

}

</style>


<script>

document.querySelectorAll('[data-promo-slideshow]').forEach(function (box) {

    const slides   = box.querySelectorAll('.promo-slide');
    const captions = box.querySelectorAll('.promo-caption-slide');
    const dots     = box.querySelectorAll('.promo-dot');
    const stage    = box.querySelector('.promo-stage');

    if (slides.length < 2) {
        return;
    }

    const delay  = 5000;   // 5 seconds on each promo
    const reduce = window.matchMedia('(prefers-reduced-motion: reduce)');

    let current  = 0;
    let timer    = null;
    let hovering = false;
    let focused  = false;


    function show(index) {

        slides[current].classList.remove('is-active');
        captions[current].classList.remove('is-active');
        if (dots[current]) { dots[current].classList.remove('is-active'); }

        current = (index + slides.length) % slides.length;

        slides[current].classList.add('is-active');
        captions[current].classList.add('is-active');
        if (dots[current]) { dots[current].classList.add('is-active'); }
    }

    function stop() {
        clearInterval(timer);
        timer = null;
    }

    function start() {
        stop();

        // never slide while the mouse is over it or a button has focus
        if (reduce.matches || hovering || focused) {
            return;
        }

        timer = setInterval(function () { show(current + 1); }, delay);
    }


    box.querySelector('.promo-prev').addEventListener('click', function () {
        show(current - 1);
        start();
    });

    box.querySelector('.promo-next').addEventListener('click', function () {
        show(current + 1);
        start();
    });

    dots.forEach(function (dot, index) {
        dot.addEventListener('click', function () {
            show(index);
            start();
        });
    });

    // pause while the mouse is over it or a button has focus
    box.addEventListener('mouseenter', function () { hovering = true;  stop();  });
    box.addEventListener('mouseleave', function () { hovering = false; start(); });
    // only keyboard focus pauses it (a mouse click leaves focus on the button)
    box.addEventListener('focusin',    function (e) { focused = e.target.matches(':focus-visible'); if (focused) { stop(); } });
    box.addEventListener('focusout',   function () { focused  = false; start(); });

    // swipe on touch screens
    let startX = null;

    stage.addEventListener('touchstart', function (event) {
        startX = event.touches[0].clientX;
    }, { passive: true });

    stage.addEventListener('touchend', function (event) {

        if (startX === null) { return; }

        const diff = event.changedTouches[0].clientX - startX;
        startX = null;

        if (Math.abs(diff) > 50) {
            show(diff < 0 ? current + 1 : current - 1);
            start();
        }

    }, { passive: true });

    start();

});

</script>
<?php endif; ?>
<?php /**PATH C:\Users\Regis D\Desktop\Beauty-Aesthetic-Appointment-System\resources\views/partials/promo-banner.blade.php ENDPATH**/ ?>