

<?php
    $admin = $admin ?? false;

    $promoLink = auth()->check()
        ? (auth()->user()->isClient()
            ? route('client.appointments.create')
            : route('home'))
        : route('register');
?>

<section class="dashboard-header dash-slider" id="dash-slider">

    

    <div class="dash-slide dash-slide-text active">

        <div class="container">

            <span class="eyebrow"><?php echo e($eyebrow); ?></span>

            <h1><?php echo e($title); ?></h1>

            <p><?php echo e($text); ?></p>

        </div>

    </div>


    

    <div class="dash-slide dash-slide-promo">

        <div
            class="dash-promo-bg"
            style="background-image: url('<?php echo e(asset('images/promo.jpg')); ?>');"
        ></div>

        <a href="<?php echo e($admin ? route('home') : $promoLink); ?>" class="dash-promo-link">

            <img
                src="<?php echo e(asset('images/promo.jpg')); ?>"
                alt="Macayla Cares Retouch/Recolor Microbrows promo: P999 with free lashes"
            >

        </a>

        <?php if (! ($admin)): ?>

            <a class="promo-cta dash-promo-cta" href="<?php echo e($promoLink); ?>">
                Book this promo
            </a>

        <?php endif; ?>

    </div>

</section>


<style>

.dash-slider {

    position: relative;

    height: clamp(290px, 30vw, 380px);

    padding: 0 !important;

    overflow: hidden;

}

.dash-slide {

    position: absolute;

    top: 0;

    left: 0;

    width: 100%;

    height: 100%;

    transform: translateX(100%);

    transition: transform 1s ease-in-out;

    z-index: 1;

}

.dash-slide.active {

    transform: translateX(0);

    z-index: 2;

}

.dash-slide.left {

    transform: translateX(-100%);

    z-index: 1;

}

.dash-slide.right {

    transform: translateX(100%);

    z-index: 2;

}

.dash-slide-text {

    display: flex;

    align-items: center;

}

.dash-slide-text .container {

    width: min(1160px, calc(100% - 40px));

}

.dash-slide-promo {

    display: flex;

    justify-content: center;

    overflow: hidden;

}

/* blurred copy of the poster fills the sides */

.dash-promo-bg {

    position: absolute;

    inset: -30px;

    background-position: center;

    background-size: cover;

    filter: blur(26px) brightness(.9);

}

.dash-promo-link {

    position: relative;

    display: block;

    height: 100%;

}

.dash-promo-link img {

    display: block;

    height: 100%;

    width: auto;

    max-width: 100%;

    object-fit: contain;

}

.dash-promo-cta {

    position: absolute;

    right: 28px;

    bottom: 22px;

    z-index: 3;

}

@media (max-width: 900px) {

    .dash-promo-cta {

        display: none;

    }

}

</style>


<script>

document.addEventListener('DOMContentLoaded', function () {

    const slider = document.getElementById('dash-slider');

    if (!slider) {
        return;
    }

    const slides = slider.querySelectorAll('.dash-slide');

    if (slides.length < 2) {
        return;
    }

    const slideDuration      = 5000;   // 5 seconds on each slide
    const transitionDuration = 1000;   // slide animation (matches the CSS)

    let current = 0;


    function nextSlide() {

        const currentSlide = slides[current];
        const nextIndex    = (current + 1) % slides.length;
        const next         = slides[nextIndex];

        // park the next slide on the RIGHT without animating
        next.style.transition = 'none';
        next.classList.remove('active', 'left');
        next.classList.add('right');

        void next.offsetWidth;

        next.style.transition = '';

        // current slide leaves to the LEFT, next enters from the RIGHT
        currentSlide.classList.remove('active');
        currentSlide.classList.add('left');

        next.classList.remove('right');
        next.classList.add('active');

        // after it has gone, quietly put the old slide back on the right
        setTimeout(function () {

            currentSlide.style.transition = 'none';
            currentSlide.classList.remove('left');

            void currentSlide.offsetWidth;

            currentSlide.style.transition = '';

        }, transitionDuration + 50);

        current = nextIndex;
    }

    setInterval(nextSlide, slideDuration);

});

</script>
<?php /**PATH C:\Users\Regis D\Desktop\Beauty-Aesthetic-Appointment-System\resources\views/partials/dashboard-slider.blade.php ENDPATH**/ ?>