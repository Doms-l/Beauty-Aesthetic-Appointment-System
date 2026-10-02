





<?php $__env->startSection('title', 'M. Cares Beauty Services'); ?>



<?php $__env->startSection('content'); ?>





<section class="hero-section">

    <div class="container hero-grid">



        <div class="hero-copy">

            <span class="eyebrow">BEAUTY · CARE · CONFIDENCE</span>



            <h1>

                Enhance your beauty.<br>

                <em>Feel your best.</em>

            </h1>



            <p>

                Discover personalized aesthetic and beauty services

                with an easy online appointment experience at

                M. Cares Beauty Services.

            </p>



            <div class="hero-actions">

                <a class="primary-button"

                   href="<?php echo e(auth()->check()

                       ? (auth()->user()->isClient()

                           ? route('client.appointments.create')

                           : route('home'))

                       : route('register')); ?>">

                    Book an Appointment

                </a>



                <a class="secondary-button"

                   href="<?php echo e(route('services.index')); ?>">

                    Explore Services

                </a>

            </div>



            <div class="hero-note">

                <span>✦</span>

                Professional service · Simple booking · Personalized care

            </div>

        </div>



        <div class="hero-art">

            <div class="logo-orbit orbit-one"></div>

            <div class="logo-orbit orbit-two"></div>



            <img src="<?php echo e(asset('images/logo.png')); ?>"

                 alt="M. Cares Beauty Services logo">

        </div>



    </div>

</section>







<section class="founder-section">

    <div class="container founder-grid">



        <div class="founder-photo-wrap">

            <div class="founder-photo-frame">

                <img src="<?php echo e(asset('images/owner.png')); ?>"

                     alt="Founder of M. Cares Beauty Services">

            </div>

            <div class="founder-photo-decoration"></div>

        </div>



        <div class="founder-content">



            <span class="eyebrow">MEET THE FOUNDER</span>



            <h2>Marjilie Preciados Tomines</h2>



            <blockquote class="founder-quote">

                “Beauty is not about being perfect.

                It is about feeling confident, cared for,

                and beautiful in your own way.”

            </blockquote>



            <div class="founder-signature">

                <span></span>

                <div>

                    <h3>Founder & Aesthetician</h3>

                    <p>M. Cares Beauty Services</p>

                </div>

            </div>



        </div>



        <div class="founder-message">

            <h3>A Message from Our Founder</h3>



            <p>

                At M. Cares Beauty Services, we believe that every

                client deserves to feel confident, comfortable,

                and cared for.

            </p>



            <p>

                Our goal is to provide beauty services that help

                you look and feel your best while giving you

                a relaxing and welcoming experience.

            </p>



            <div class="founder-sign">

                M. Cares

                <span>♡</span>

            </div>

        </div>



    </div>

</section>







<section class="achievements-section">

    <div class="container achievements-grid">



        <div class="achievement-art achievement-photo">
            <img
            src="<?php echo e(asset('images/awards.jpg')); ?>"
            alt="M. Cares Beauty Services awards and certificates"
            width="1200"
            height="900"
            loading="lazy"
        >
        </div>

        <div class="achievement-content">



            <span class="eyebrow">AWARDS & ACHIEVEMENTS</span>



            <h2>M. Cares Beauty Services</h2>



            <p class="achievement-intro">

                Professional training, certifications, and

                continued development in beauty and aesthetics.

            </p>



            <ul class="achievement-list">

                <li>Certified IVT Nurse</li>

                <li>Certified Aesthetician</li>

                <li>Certified Advance Facial Treatments Specialist</li>

                <li>Certified SPMU Artist</li>

                <li>Master Lash Artist</li>

                <li>Medical Aesthetician</li>

                <li>NAD+ & MCCM Premier Product Workshop Participant</li>

            </ul>



        </div>



        <div class="achievement-motto">

            <h3>Beauty Beyond Confidence</h3>

            <span></span>

            <p>

                Professional care, continuous learning,

                and personalized beauty services.

            </p>

        </div>



    </div>

</section>







<section class="home-services-section">

    <div class="container">



        <div class="section-heading">

            <span class="eyebrow">OUR SERVICES</span>

            <h2>Beauty Services We Offer</h2>

            <p>

                Explore treatments designed to help you look

                and feel your best.

            </p>

        </div>



        <div class="home-category-grid">



            <a href="<?php echo e(route('services.index')); ?>"

               class="home-category-card">

                <div class="category-visual facial-visual">

                    <span>FACIAL</span>

                </div>

                <h3>Facial Treatments</h3>

                <p>Cleanse · Rejuvenate · Glow</p>

                <span class="category-button">View Services</span>

            </a>



            <a href="<?php echo e(route('services.index')); ?>"

               class="home-category-card">

                <div class="category-visual lashes-visual">

                    <span>LASHES</span>

                </div>

                <h3>Lashes</h3>

                <p>Longer · Fuller · Defined</p>

                <span class="category-button">View Services</span>

            </a>



            <a href="<?php echo e(route('services.index')); ?>"

               class="home-category-card">

                <div class="category-visual brows-visual">

                    <span>BROWS</span>

                </div>

                <h3>Brows</h3>

                <p>Shape · Enhance · Frame</p>

                <span class="category-button">View Services</span>

            </a>



            <a href="<?php echo e(route('services.index')); ?>"

               class="home-category-card">

                <div class="category-visual body-visual">

                    <span>BODY</span>

                </div>

                <h3>Body Treatments</h3>

                <p>Smooth · Firm · Renew</p>

                <span class="category-button">View Services</span>

            </a>



            <a href="<?php echo e(route('services.index')); ?>"

               class="home-category-card">

                <div class="category-visual advanced-visual">

                    <span>AESTHETIC</span>

                </div>

                <h3>Advanced Treatments</h3>

                <p>Modern · Safe · Effective</p>

                <span class="category-button">View Services</span>

            </a>



        </div>



        <div class="center-button">

            <a class="secondary-button"

               href="<?php echo e(route('services.index')); ?>">

                Explore All Services

            </a>

        </div>



    </div>

</section>







<section class="why-mcares-section">

    <div class="container">



        <div class="section-heading">

            <span class="eyebrow">WHY CHOOSE M. CARES</span>

            <h2>Your Beauty, Our Priority</h2>

        </div>



        <div class="why-grid">



            <div class="why-item">

                <div class="why-icon">♧</div>

                <h3>Professional & Certified</h3>

                <p>With trusted training and experience.</p>

            </div>



            <div class="why-item">

                <div class="why-icon">♡</div>

                <h3>Personalized Care</h3>

                <p>Tailored treatments for your unique needs.</p>

            </div>



            <div class="why-item">

                <div class="why-icon">✿</div>

                <h3>Safe & Hygienic</h3>

                <p>Clean, safe, and comfortable environment.</p>

            </div>



            <div class="why-item">

                <div class="why-icon">☆</div>

                <h3>Modern Techniques</h3>

                <p>Latest products and technology.</p>

            </div>



            <div class="why-item">

                <div class="why-icon">◷</div>

                <h3>Convenient Booking</h3>

                <p>Easy online appointment system.</p>

            </div>



        </div>

    </div>

</section>







<section class="home-cta-section">

    <div class="container home-cta-content">



        <h2>Ready to look and feel your best?</h2>



        <div class="home-cta-center">

            <h3>Book Your Appointment Today</h3>

            <p>

                Take the first step towards a more confident you.

            </p>

        </div>



        <a class="cta-outline-button"

           href="<?php echo e(auth()->check()

               ? (auth()->user()->isClient()

                   ? route('client.appointments.create')

                   : route('home'))

               : route('register')); ?>">

            Book Now →

        </a>



    </div>

</section>



<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Jennely\Desktop\Beauty-Aesthetic-Appointment-System\resources\views/home.blade.php ENDPATH**/ ?>