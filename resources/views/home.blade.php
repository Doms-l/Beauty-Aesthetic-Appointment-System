@extends('layouts.app')



@section('title', 'M. Cares Beauty Services')



@section('content')



{{-- HERO --}}

@php
    /*
    |--------------------------------------------------------------------------
    | HERO PHOTOS
    | The 3 pop-up photos are picked RANDOMLY from these folders
    | (inside public/images) every time a service slide appears.
    | New photos you add to these folders are included automatically.
    |--------------------------------------------------------------------------
    */
    $heroFolders = [
        'facial' => 'Facial Services',
        'lash'   => 'Lash and Brows Services',
        'other'  => 'Other Services',
    ];

    // makes file names with spaces safe for the browser
    $heroSrc = function ($path) {
        return asset('images/' . implode('/', array_map('rawurlencode', explode('/', $path))));
    };

    $heroPools  = [];
    $heroPhotos = [];

    foreach ($heroFolders as $key => $folder) {

        $dir   = public_path('images/' . $folder);
        $files = [];

        if (is_dir($dir)) {
            foreach (scandir($dir) as $file) {
                $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));

                if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
                    $files[] = $heroSrc($folder . '/' . $file);
                }
            }
        }

        shuffle($files);

        $heroPools[$key]  = $files;
        $heroPhotos[$key] = array_slice($files, 0, 3);
    }
@endphp

<section class="hero-section">

    <div class="container hero-grid">


        <div class="hero-copy">

            {{-- FIXED --}}
            <span class="eyebrow">BEAUTY · CARE · CONFIDENCE</span>


            {{-- ONLY THIS PART SLIDES --}}
            <div class="hero-text-slider" id="hero-text-slider">

                <div class="hero-text-slide active">

                    <h1>
                        Enhance your beauty.<br>
                        <em>Feel your best.</em>
                    </h1>

                    <p>
                        Discover personalized aesthetic and beauty services
                        with an easy online appointment experience at
                        M. Cares Beauty Services.
                    </p>

                </div>


                <div class="hero-text-slide" aria-hidden="true">

                    <h1>
                        M. CARES<br>
                        <em>Facial Services</em>
                    </h1>

                    <p>
                        Cleanse, rejuvenate and glow with personalized
                        facial treatments made for your skin.
                    </p>

                </div>


                <div class="hero-text-slide" aria-hidden="true">

                    <h1>
                        M. CARES<br>
                        <em>Lash and Brows Services</em>
                    </h1>

                    <p>
                        Longer, fuller and beautifully defined lashes
                        and brows that frame your best look.
                    </p>

                </div>


                <div class="hero-text-slide" aria-hidden="true">

                    <h1>
                        M. CARES<br>
                        <em>Other Services</em>
                    </h1>

                    <p>
                        Body and advanced aesthetic treatments to help
                        you feel smooth, firm and renewed.
                    </p>

                </div>

            </div>


            {{-- FIXED --}}
            <div class="hero-actions">

                <a class="primary-button"

                   href="{{ auth()->check()

                       ? (auth()->user()->isClient()

                           ? route('client.appointments.create')

                           : route('home'))

                       : route('register') }}">

                    Book an Appointment

                </a>


                <a class="secondary-button"

                   href="{{ route('services.index') }}">

                    Explore Services

                </a>

            </div>


            {{-- FIXED --}}
            <div class="hero-note">

                <span>✦</span>

                Professional service · Simple booking · Personalized care

            </div>

        </div>


        {{-- FIXED LOGO + POP-UP PHOTOS --}}
        <div class="hero-art">

            <div class="logo-orbit orbit-one"></div>

            <div class="logo-orbit orbit-two"></div>


            {{-- Photos for slide 2: Facial --}}
            <div class="hero-photo-set" data-slide="1" data-pool="facial">
                @foreach($heroPhotos['facial'] as $i => $photo)
                    <img class="hero-photo hero-photo-{{ $i + 1 }}"
                         src="{{ $photo }}"
                         alt="M. Cares facial services">
                @endforeach
            </div>

            {{-- Photos for slide 3: Lash and Brows --}}
            <div class="hero-photo-set" data-slide="2" data-pool="lash">
                @foreach($heroPhotos['lash'] as $i => $photo)
                    <img class="hero-photo hero-photo-{{ $i + 1 }}"
                         src="{{ $photo }}"
                         alt="M. Cares lash and brows services">
                @endforeach
            </div>

            {{-- Photos for slide 4: Other Services --}}
            <div class="hero-photo-set" data-slide="3" data-pool="other">
                @foreach($heroPhotos['other'] as $i => $photo)
                    <img class="hero-photo hero-photo-{{ $i + 1 }}"
                         src="{{ $photo }}"
                         alt="M. Cares other services">
                @endforeach
            </div>


            <img src="{{ asset('images/logo.png') }}"

                 alt="M. Cares Beauty Services logo">

        </div>


    </div>

</section>


<style>

/* =========================================================
   HERO TEXT SLIDER (logo, buttons and note stay fixed)
   ========================================================= */

.hero-text-slider {
    display: grid;
}

.hero-text-slide {
    grid-area: 1 / 1;

    opacity: 0;
    transform: translateX(90px);

    transition:
        transform .9s ease,
        opacity .9s ease;

    pointer-events: none;
}

.hero-text-slide.active {
    opacity: 1;
    transform: translateX(0);
    pointer-events: auto;
}

/* slides that already played leave toward the LEFT */
.hero-text-slide.past {
    transform: translateX(-90px);
}

.hero-text-slide h1 {
    font-family: 'Playfair Display', Georgia, serif;
    font-size: clamp(48px, 6vw, 78px);
    line-height: 1.03;
    margin: 16px 0 24px;
    color: var(--heading);
}

.hero-text-slide h1 em {
    color: #9a6478;
    font-weight: 500;
}

.hero-text-slide p {
    max-width: 590px;
    margin: 0;
    color: var(--text-muted);
    font-size: 18px;
}


/* =========================================================
   POP-UP PHOTOS BEHIND THE LOGO
   ========================================================= */

.hero-photo-set {
    position: absolute;
    inset: 0;
    z-index: 1;
    pointer-events: none;
}

/* .hero-art img (main CSS) styles the big round logo, so this
   selector is more specific to keep the photos as small cards */
.hero-art .hero-photo {
    position: absolute;
    z-index: auto;

    width: clamp(110px, 14vw, 175px);
    height: auto;
    aspect-ratio: 4 / 5;
    object-fit: cover;

    border-radius: 18px;
    border: 4px solid rgba(255, 255, 255, .88);
    box-shadow: var(--shadow);

    opacity: 0;
    transform: scale(.4) rotate(var(--rot));

    transition:
        opacity .35s ease,
        transform .35s ease;
}

.hero-art .hero-photo-1 { top: -10px;    left: -45px;  --rot: -8deg; }
.hero-art .hero-photo-2 { top: 130px;    right: -50px; --rot: 7deg;  }
.hero-art .hero-photo-3 { bottom: -15px; left: -25px;  --rot: -4deg; }

/* pop in one after another */
.hero-art .hero-photo-set.active .hero-photo {
    opacity: 1;
    transform: scale(1) rotate(var(--rot));

    transition:
        opacity .6s ease,
        transform .7s cubic-bezier(.34, 1.56, .64, 1);
}

.hero-art .hero-photo-set.active .hero-photo-1 { transition-delay: .35s; }
.hero-art .hero-photo-set.active .hero-photo-2 { transition-delay: .6s;  }
.hero-art .hero-photo-set.active .hero-photo-3 { transition-delay: .85s; }


@media (max-width: 900px) {

    .hero-art .hero-photo {
        width: clamp(90px, 22vw, 130px);
    }

    .hero-art .hero-photo-1 { left: -15px;  }
    .hero-art .hero-photo-2 { right: -15px; }
    .hero-art .hero-photo-3 { left: -5px;   }

}

@media (prefers-reduced-motion: reduce) {

    .hero-text-slide,
    .hero-photo {
        transition-duration: .01s !important;
        transition-delay: 0s !important;
    }

}

</style>


<script>

document.addEventListener('DOMContentLoaded', function () {

    const textSlides = document.querySelectorAll('.hero-text-slide');
    const photoSets  = document.querySelectorAll('.hero-photo-set');

    // all photos found in each service folder
    const pools = @json($heroPools);

    if (textSlides.length < 2) {
        return;
    }

    const slideDuration = 5500;   // time on each text (ms)
    let current = 0;


    // pick 3 different random photos from the folder
    function randomizePhotos(set) {

        const pool = (pools[set.dataset.pool] || []).slice();

        // shuffle
        for (let i = pool.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [pool[i], pool[j]] = [pool[j], pool[i]];
        }

        set.querySelectorAll('.hero-photo').forEach(function (img, i) {

            if (pool[i]) {
                img.src = pool[i];
                img.style.display = '';
            } else {
                img.style.display = 'none';
            }

        });
    }


    function showSlide(index) {

        textSlides.forEach(function (slide, i) {

            slide.classList.toggle('active', i === index);
            slide.classList.toggle('past', i < index);
            slide.setAttribute('aria-hidden', i === index ? 'false' : 'true');

        });

        // photos pop up only on the service slides
        photoSets.forEach(function (set) {

            set.classList.toggle(
                'active',
                Number(set.dataset.slide) === index
            );

        });

        current = index;

        // after the old photos have faded out, choose new random ones
        // for the NEXT time those slides appear
        setTimeout(function () {

            photoSets.forEach(function (set) {

                if (!set.classList.contains('active')) {
                    randomizePhotos(set);
                }

            });

        }, 1200);
    }

    showSlide(0);

    setInterval(function () {

        showSlide((current + 1) % textSlides.length);

    }, slideDuration);

});

</script>





{{-- SERVICE CATEGORY PHOTOS --}}

<style>

/*
|--------------------------------------------------------------------------
| Photos inside the service category cards
| (change the file names in the HTML below the "OUR SERVICES" heading)
|--------------------------------------------------------------------------
*/

.home-category-card .category-visual {

    position: relative;

    height: 130px;

}

.home-category-card .category-visual img {

    position: absolute;

    top: 0;

    left: 0;

    width: 100%;

    height: 100%;

    object-fit: cover;

    transition: transform .5s ease;

}

.home-category-card:hover .category-visual img {

    transform: scale(1.08);

}

/* soft dark layer so the white label stays readable */

.home-category-card .category-visual::after {

    content: "";

    position: absolute;

    inset: 0;

    background: linear-gradient(180deg, rgba(98, 68, 77, .15), rgba(60, 35, 45, .55));

}

.home-category-card .category-visual span {

    position: relative;

    z-index: 1;

    color: #ffffff;

    text-shadow: 0 2px 8px rgba(0, 0, 0, .45);

}

@media (max-width: 650px) {

    .home-category-card .category-visual {

        height: 105px;

    }

}

</style>


{{-- PROMO --}}
@include('partials.promo-banner')

{{-- MEET THE FOUNDER --}}

<section class="founder-section">

    <div class="container founder-grid">

        {{-- LEFT: FOUNDER PHOTO --}}
        <div class="founder-photo-wrap">

            <div class="founder-photo-frame">

                <img
                    src="{{ asset('images/owner.png') }}"
                    alt="Founder of M. Cares Beauty Services"
                >

            </div>

            <div class="founder-photo-decoration"></div>

        </div>


        {{-- RIGHT TOP: FOUNDER INFORMATION --}}
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


        {{-- RIGHT BOTTOM: MESSAGE --}}
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


{{-- M. Cares Photo Slideshow --}}
<section class="mcares-slideshow-section">
    <div class="container">

        <div class="mcares-slideshow-header">
            <span class="eyebrow">M. CARES BEAUTY SERVICES</span>
            <h2>A Look Inside M. Cares</h2>
            <p>Experience our space, services, and beauty care.</p>
        </div>

        <div class="mcares-slideshow">

            <div class="mcares-slide active">
                <img
                    src="{{ asset('images/m.care1.jpg') }}"
                    alt="M. Cares Beauty Services"
                >
            </div>

            <div class="mcares-slide">
                <img
                    src="{{ asset('images/m.care2.jpg') }}"
                    alt="M. Cares Beauty Services"
                >
            </div>

            <div class="mcares-slide">
                <img
                    src="{{ asset('images/m.care3.jpg') }}"
                    alt="M. Cares Beauty Services"
                >
            </div>

            <div class="mcares-slide">
                <img
                    src="{{ asset('images/m.care4.jpg') }}"
                    alt="M. Cares Beauty Services"
                >
            </div>

            <div class="mcares-slide">
                <img
                    src="{{ asset('images/m.care5.jpg') }}"
                    alt="M. Cares Beauty Services"
                >
            </div>

            <div class="mcares-slide">
                <img
                    src="{{ asset('images/m.care6.jpg') }}"
                    alt="M. Cares Beauty Services"
                >
            </div>

            <button class="mcares-slide-btn prev" type="button" aria-label="Previous photo">
                &#10094;
            </button>

            <button class="mcares-slide-btn next" type="button" aria-label="Next photo">
                &#10095;
            </button>

        </div>

        <div class="mcares-slide-dots">
            <button class="mcares-dot active" type="button" aria-label="Photo 1"></button>
            <button class="mcares-dot" type="button" aria-label="Photo 2"></button>
            <button class="mcares-dot" type="button" aria-label="Photo 3"></button>
            <button class="mcares-dot" type="button" aria-label="Photo 4"></button>
            <button class="mcares-dot" type="button" aria-label="Photo 5"></button>
            <button class="mcares-dot" type="button" aria-label="Photo 6"></button>
        </div>

        <p class="mcares-slide-caption">
            Photo 1 of 6 &nbsp; • &nbsp; Changes every 5 seconds
        </p>

    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const slides = document.querySelectorAll('.mcares-slide');
    const dots = document.querySelectorAll('.mcares-dot');
    const prevButton = document.querySelector('.mcares-slide-btn.prev');
    const nextButton = document.querySelector('.mcares-slide-btn.next');
    const caption = document.querySelector('.mcares-slide-caption');

    if (!slides.length) return;

    let currentSlide = 0;
    let slideshowTimer;

    function showSlide(index) {
        slides.forEach((slide, i) => {
            slide.classList.toggle('active', i === index);
        });

        dots.forEach((dot, i) => {
            dot.classList.toggle('active', i === index);
        });

        caption.innerHTML =
            ' ' + (index + 1) + ' of 6 &nbsp; • &nbsp; ';
 
        currentSlide = index;
    }

    function nextSlide() {
        currentSlide = (currentSlide + 1) % slides.length;
        showSlide(currentSlide);
    }

    function previousSlide() {
        currentSlide = (currentSlide - 1 + slides.length) % slides.length;
        showSlide(currentSlide);
    }

    function startSlideshow() {
        clearInterval(slideshowTimer);
        slideshowTimer = setInterval(nextSlide, 5000);
    }

    nextButton.addEventListener('click', function () {
        nextSlide();
        startSlideshow();
    });

    prevButton.addEventListener('click', function () {
        previousSlide();
        startSlideshow();
    });

    dots.forEach((dot, index) => {
        dot.addEventListener('click', function () {
            showSlide(index);
            startSlideshow();
        });
    });

    showSlide(0);
    startSlideshow();
});
</script>

{{-- AWARDS AND ACHIEVEMENTS --}}

<section class="achievements-section">

    <div class="container achievements-grid">



        <div class="achievement-art achievement-photo">
            <img
            src="{{ asset('images/awards.jpg') }}"
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





{{-- SERVICES --}}

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



            <a href="{{ route('services.index') }}"

               class="home-category-card">

                <div class="category-visual facial-visual">

                    <img src="{{ $heroSrc('Facial Services/Hydra Facial.jpg') }}" alt="Facial treatments" loading="lazy">

                    <span>FACIAL</span>

                </div>

                <h3>Facial Treatments</h3>

                <p>Cleanse · Rejuvenate · Glow</p>

                <span class="category-button">View Services</span>

            </a>



            <a href="{{ route('services.index') }}"

               class="home-category-card">

                <div class="category-visual lashes-visual">

                    <img src="{{ $heroSrc('Lash and Brows Services/Lash Extension.jpg') }}" alt="Lash extensions" loading="lazy">

                    <span>LASHES</span>

                </div>

                <h3>Lashes</h3>

                <p>Longer · Fuller · Defined</p>

                <span class="category-button">View Services</span>

            </a>



            <a href="{{ route('services.index') }}"

               class="home-category-card">

                <div class="category-visual brows-visual">

                    <img src="{{ $heroSrc('Lash and Brows Services/Brow Lamination with Tint.jpg') }}" alt="Brow lamination with tint" loading="lazy">

                    <span>BROWS</span>

                </div>

                <h3>Brows</h3>

                <p>Shape · Enhance · Frame</p>

                <span class="category-button">View Services</span>

            </a>



            <a href="{{ route('services.index') }}"

               class="home-category-card">

                <div class="category-visual body-visual">

                    <img src="{{ $heroSrc('Other Services/Barbie Arms.jpg') }}" alt="Body treatments" loading="lazy">

                    <span>BODY</span>

                </div>

                <h3>Body Treatments</h3>

                <p>Smooth · Firm · Renew</p>

                <span class="category-button">View Services</span>

            </a>



            <a href="{{ route('services.index') }}"

               class="home-category-card">

                <div class="category-visual advanced-visual">

                    <img src="{{ $heroSrc('Other Services/Face Botox.jpg') }}" alt="Advanced aesthetic treatments" loading="lazy">

                    <span>AESTHETIC</span>

                </div>

                <h3>Advanced Treatments</h3>

                <p>Modern · Safe · Effective</p>

                <span class="category-button">View Services</span>

            </a>



        </div>



        <div class="center-button">

            <a class="secondary-button"

               href="{{ route('services.index') }}">

                Explore All Services

            </a>

        </div>



    </div>

</section>





{{-- WHY CHOOSE US --}}

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





{{-- BOOKING CTA --}}

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

           href="{{ auth()->check()

               ? (auth()->user()->isClient()

                   ? route('client.appointments.create')

                   : route('home'))

               : route('register') }}">

            Book Now →

        </a>



    </div>

</section>



@endsection