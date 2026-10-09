<?php
    use Illuminate\Support\Facades\Storage;
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <meta
        name="csrf-token"
        content="<?php echo e(csrf_token()); ?>"
    >

    <meta
        name="color-scheme"
        content="light dark"
    >

    <title>
        <?php echo $__env->yieldContent('title', 'M. Cares Beauty Services'); ?>
    </title>

    <link
        rel="icon"
        type="image/png"
        href="<?php echo e(asset('images/round.png')); ?>"
    >

    
    <script>
        (function () {

            // loading screen only on the first visit of this browser tab
            try {
                if (sessionStorage.getItem('mcares-loader-seen')) {
                    document.documentElement.classList.add('mcares-seen');
                }
            } catch (e) {}

            const savedTheme =
                localStorage.getItem('mcares-theme');

            if (savedTheme === 'dark') {

                document.documentElement.classList.add('dark-mode');

            } else {

                document.documentElement.classList.remove('dark-mode');

            }

        })();
    </script>

    <?php echo app('Illuminate\Foundation\Vite')->reactRefresh(); ?>

    <?php echo app('Illuminate\Foundation\Vite')([
        'resources/css/app.css',
        'resources/js/app.jsx'
    ]); ?>

</head>


<body>


<style>
    #mcares-loader {
        position: fixed;
        inset: 0;
        z-index: 2147483000;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 22px;
        background: #fffafc;
        transition: opacity .5s ease, visibility .5s ease;
    }
    html.dark-mode #mcares-loader { background: #161316; }

    html.mcares-seen #mcares-loader { display: none !important; }

    #mcares-loader.is-hidden { opacity: 0; visibility: hidden; pointer-events: none; }

    #mcares-loader .loader-logo {
        width: 120px;
        height: 120px;
        object-fit: contain;
        border-radius: 50%;
        animation: mcares-pulse 1.6s ease-in-out infinite;
    }
    #mcares-loader .loader-name {
        margin: 0;
        font-family: 'Playfair Display', Georgia, serif;
        font-size: 1.5rem;
        letter-spacing: .02em;
        color: #62444D;
    }
    html.dark-mode #mcares-loader .loader-name { color: #f3d9e4; }

    #mcares-loader .loader-bar {
        width: 220px;
        height: 4px;
        border-radius: 999px;
        overflow: hidden;
        background: rgba(98, 68, 77, .15);
    }
    html.dark-mode #mcares-loader .loader-bar { background: rgba(255, 255, 255, .15); }

    #mcares-loader .loader-bar span {
        display: block;
        width: 0;
        height: 100%;
        border-radius: 999px;
        background: linear-gradient(90deg, #F3BCD2, #FFAFF1, #D6B36A);
        transition: width .35s ease;
    }

    @keyframes mcares-pulse {
        0%, 100% { transform: scale(1);    opacity: .9; }
        50%      { transform: scale(1.08); opacity: 1; }
    }

    body.mcares-loading { overflow: hidden; }
</style>

<noscript><style>#mcares-loader { display: none !important; }</style></noscript>

<div id="mcares-loader" role="status" aria-label="Loading">
    <img class="loader-logo" src="<?php echo e(asset('images/round.png')); ?>" alt="M. Cares Beauty Services">
    <p class="loader-name">M. Cares Beauty Services</p>
    <div class="loader-bar"><span id="mcares-loader-fill"></span></div>
</div>

<script>
    (function () {

        const loader = document.getElementById('mcares-loader');
        const fill   = document.getElementById('mcares-loader-fill');

        // already shown in this tab: do not show it again on other pages
        if (document.documentElement.classList.contains('mcares-seen')) {
            loader.remove();
            return;
        }

        const started = Date.now();
        const MIN_SHOW = 1100;      // always show at least ~1 second
        let progress = 0;
        let done = false;

        document.body.classList.add('mcares-loading');

        // the bar creeps forward while the page loads
        const timer = setInterval(function () {
            progress += (90 - progress) * 0.12;
            fill.style.width = progress + '%';
        }, 120);

        function finish() {

            if (done) { return; }
            done = true;

            const wait = Math.max(0, MIN_SHOW - (Date.now() - started));

            setTimeout(function () {
                clearInterval(timer);
                fill.style.width = '100%';

                setTimeout(function () {
                    try { sessionStorage.setItem('mcares-loader-seen', '1'); } catch (e) {}
                    loader.classList.add('is-hidden');
                    document.body.classList.remove('mcares-loading');
                    setTimeout(function () { loader.remove(); }, 700);
                }, 250);
            }, wait);
        }

        if (document.readyState === 'complete') {
            finish();
        } else {
            window.addEventListener('load', finish);
        }

        // back/forward button (page restored from cache)
        window.addEventListener('pageshow', function (e) { if (e.persisted) { finish(); } });

        // safety: never get stuck
        setTimeout(finish, 8000);

    })();
</script>



<?php
    $barLink = auth()->check()
        ? (auth()->user()->isClient()
            ? route('client.appointments.create')
            : route('home'))
        : route('register');
?>

<div class="promo-bar" id="promo-bar">

    <a href="<?php echo e($barLink); ?>">
        &#127872; <strong>PROMO:</strong>
        Retouch/Recolor Microbrows only <strong>&#8369;999</strong>
        with FREE Lashes &mdash; Book now &rarr;
    </a>

    <button
        type="button"
        id="promo-bar-close"
        class="promo-bar-close"
        aria-label="Close promo bar"
    >
        &times;
    </button>

</div>




<header class="site-header">

    <div class="container nav-wrap">

        

        <a
            class="brand"
            href="<?php echo e(route('home')); ?>"
        >

            <img
                src="<?php echo e(asset('images/logo.png')); ?>"
                alt="M. Cares Beauty Services logo"
            >

            <span>
                M. CARES<br>
                <small>BEAUTY SERVICES</small>
            </span>

        </a>


        

        <nav class="main-nav">

            <a href="<?php echo e(route('home')); ?>">
                Home
            </a>


            <a href="<?php echo e(route('services.index')); ?>">
                Services
            </a>


            <?php if(auth()->guard()->check()): ?>

                

                <?php if(auth()->user()->isClient()): ?>

                    <a href="<?php echo e(route('client.dashboard')); ?>">
                        Dashboard
                    </a>


                    <a href="<?php echo e(route('client.appointments')); ?>">
                        Appointments
                    </a>


                    <a
                        class="nav-cta"
                        href="<?php echo e(route('client.appointments.create')); ?>"
                    >
                        Book Now
                    </a>


                    <a
                        href="<?php echo e(route('client.profile')); ?>"
                        class="nav-profile"
                        title="Edit Profile"
                        aria-label="Edit Profile"
                    >

                        <?php if(auth()->user()->profile_picture): ?>

                            <img
                                src="<?php echo e(Storage::disk('public')->url(auth()->user()->profile_picture)); ?>"
                                alt="Profile Picture"
                            >

                        <?php else: ?>

                            <span>
                                <?php echo e(strtoupper(substr(auth()->user()->first_name, 0, 1))); ?>

                            </span>

                        <?php endif; ?>

                    </a>


                

                <?php elseif(auth()->user()->isAdmin()): ?>

                    <a href="<?php echo e(route('admin.dashboard')); ?>">
                        Admin Dashboard
                    </a>


                    <a
                        href="<?php echo e(route('admin.profile')); ?>"
                        class="nav-profile"
                        title="Admin Profile"
                        aria-label="Admin Profile"
                    >

                        <?php if(auth()->user()->profile_picture): ?>

                            <img
                                src="<?php echo e(Storage::disk('public')->url(auth()->user()->profile_picture)); ?>"
                                alt="Admin Profile Picture"
                            >

                        <?php else: ?>

                            <span>
                                <?php echo e(strtoupper(substr(auth()->user()->first_name, 0, 1))); ?>

                            </span>

                        <?php endif; ?>

                    </a>


                

                <?php elseif(auth()->user()->isStaff()): ?>

                    <a href="<?php echo e(route('staff.dashboard')); ?>">
                        Staff Dashboard
                    </a>

                <?php endif; ?>


                

                <form
                    method="POST"
                    action="<?php echo e(route('logout')); ?>"
                    class="inline-form"
                >

                    <?php echo csrf_field(); ?>

                    <button
                        type="submit"
                        class="link-button"
                    >
                        Logout
                    </button>

                </form>


                

                <button
                    type="button"
                    id="theme-toggle"
                    class="theme-toggle"
                    aria-label="Switch to dark mode"
                    title="Switch to dark mode"
                >

                    <span id="theme-icon">
                        🌙
                    </span>

                </button>


            <?php else: ?>

                

                <a href="<?php echo e(route('login')); ?>">
                    Login
                </a>


                <a
                    class="nav-cta"
                    href="<?php echo e(route('register')); ?>"
                >
                    Register
                </a>


                

                <button
                    type="button"
                    id="theme-toggle"
                    class="theme-toggle"
                    aria-label="Switch to dark mode"
                    title="Switch to dark mode"
                >

                    <span id="theme-icon">
                        🌙
                    </span>

                </button>

            <?php endif; ?>

        </nav>

    </div>

</header>




<?php if(session('success')): ?>

    <div class="container flash success">

        <?php echo e(session('success')); ?>


    </div>

<?php endif; ?>




<?php if($errors->any()): ?>

    <div class="container flash error">

        <strong>
            Please check the form.
        </strong>

        <ul>

            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                <li>
                    <?php echo e($error); ?>

                </li>

            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        </ul>

    </div>

<?php endif; ?>




<main>

    <?php echo $__env->yieldContent('content'); ?>

</main>




<div class="mcares-chatbot">

    <button
        type="button"
        id="mcares-chat-toggle"
        class="mcares-chat-toggle"
        aria-label="Open M. Cares chatbot"
        aria-expanded="false"
    >

        <img
            src="<?php echo e(asset('images/chatbot.png')); ?>"
            alt="M. Cares Chatbot"
            class="mcares-chat-toggle-img"
        >

    </button>


    <div
        id="mcares-chat-window"
        class="mcares-chat-window"
        aria-hidden="true"
    >

        

        <div class="mcares-chat-header">

            <div class="mcares-chat-header-info">

                <div class="mcares-chat-avatar">

                    <img
                        src="<?php echo e(asset('images/chatbot.png')); ?>"
                        alt="M. Cares Assistant"
                    >

                </div>


                <div>

                    <strong>
                        M. Cares Assistant
                    </strong>

                    <small>
                        We're here to help
                    </small>

                </div>

            </div>


            <button
                type="button"
                id="mcares-chat-close"
                class="mcares-chat-close"
                aria-label="Close chatbot"
            >
                ×
            </button>

        </div>


        

        <div
            id="mcares-chat-messages"
            class="mcares-chat-messages"
        >

            <div class="mcares-chat-message bot">

                <div class="mcares-chat-bubble">

                    Hi! 👋

                    <br><br>

                    Welcome to
                    <strong>M. Cares Beauty Services</strong>.

                    <br><br>

                    How can I help you today?

                </div>

            </div>

        </div>


        

        <div class="mcares-chat-quick">

            <button
                type="button"
                data-question="What services do you offer?"
            >
                Services
            </button>


            <button
                type="button"
                data-question="How can I book an appointment?"
            >
                Book Appointment
            </button>


            <button
                type="button"
                data-question="What amenities are available?"
            >
                Amenities
            </button>


            <button
                type="button"
                data-question="What are your clinic hours?"
            >
                Clinic Hours
            </button>


            <button
                type="button"
                data-question="Where is your clinic located?"
            >
                Location
            </button>

        </div>


        

        <form
            id="mcares-chat-form"
            class="mcares-chat-form"
            data-endpoint="<?php echo e(route('chatbot')); ?>"
        >

            <input
                type="text"
                id="mcares-chat-input"
                placeholder="Type your question..."
                autocomplete="off"
                maxlength="500"
            >


            <button
                type="submit"
                aria-label="Send message"
            >
                Send
            </button>

        </form>

    </div>

</div>




<footer class="site-footer">

    <div class="footer-container">

        

        <div class="footer-brand">

            <img
                src="<?php echo e(asset('images/round.png')); ?>"
                alt="M. Cares Beauty Services"
                class="footer-logo"
            >

            <span class="footer-brand-name">
                M. CARES
                <small>BEAUTY SERVICES</small>
            </span>

        </div>


        

        <div class="footer-contact">

            

            <a
                href="https://www.facebook.com/macaylaanjeaneath.raejell"
                target="_blank"
                rel="noopener noreferrer"
                class="footer-contact-item"
            >

                <span class="footer-icon">
                    f
                </span>

                <span>
                    Macayla Cares
                </span>

            </a>


            

            <a
                href="tel:09155168312"
                class="footer-contact-item"
            >

                <span class="footer-icon">
                    ☎
                </span>

                <span>
                    09155168312
                </span>

            </a>

        </div>


        

        <div class="footer-copyright">

            <p>
                © <?php echo e(date('Y')); ?> M. Cares Beauty Services.
                All rights reserved.
            </p>

        </div>

    </div>

</footer>




<style>

.site-footer {

    background: #222222;

    color: #ffffff;

    padding: 28px 0;

}


.footer-container {

    width: min(1200px, 92%);

    margin: 0 auto;

    display: flex;

    align-items: center;

    justify-content: space-between;

    gap: 30px;

}


.footer-brand {

    display: flex;

    align-items: center;

    gap: 14px;

}


.footer-brand-name {

    display: flex;

    flex-direction: column;

    color: #ffffff;

    font-family: 'Playfair Display', Georgia, serif;

    font-size: 20px;

    font-weight: 600;

    letter-spacing: 1px;

    line-height: 1.2;

}


.footer-brand-name small {

    margin-top: 4px;

    color: #e8c9d9;

    font-family: 'DM Sans', Arial, sans-serif;

    font-size: 9px;

    font-weight: 500;

    letter-spacing: 2.4px;

}


.footer-logo {

    width: 75px;

    height: 75px;

    object-fit: contain;

    display: block;

}


.footer-contact {

    display: flex;

    align-items: center;

    gap: 25px;

}


.footer-contact-item {

    display: flex;

    align-items: center;

    gap: 9px;

    color: #ffffff;

    text-decoration: none;

    font-size: 15px;

    transition: 0.3s ease;

}


.footer-contact-item:hover {

    color: #e91e8c;

}


.footer-icon {

    width: 32px;

    height: 32px;

    display: flex;

    align-items: center;

    justify-content: center;

    border-radius: 50%;

    background: #e91e8c;

    color: #ffffff;

    font-weight: bold;

    font-size: 18px;

}


.footer-copyright p {

    margin: 0;

    font-size: 13px;

    color: #cccccc;

    text-align: right;

}


@media (max-width: 700px) {

    .footer-container {

        flex-direction: column;

        text-align: center;

    }


    .footer-contact {

        flex-direction: column;

        gap: 15px;

    }


    .footer-copyright p {

        text-align: center;

    }

}

</style>




<style>

/* =========================================================
   THEME BUTTON
========================================================= */

.theme-toggle {

    width: 40px;

    height: 40px;

    padding: 0;

    margin-left: 8px;

    border: 1px solid #e8aecf;

    border-radius: 50%;

    background: #fff7fb;

    color: #62444d;

    display: inline-flex;

    align-items: center;

    justify-content: center;

    cursor: pointer;

    font-size: 18px;

    line-height: 1;

    transition:
        background 0.3s ease,
        color 0.3s ease,
        border-color 0.3s ease,
        transform 0.3s ease;

}


.theme-toggle:hover {

    background: #e8aecf;

    color: #ffffff;

    border-color: #e8aecf;

    transform: rotate(10deg);

}


/* =========================================================
   DARK MODE
========================================================= */

html.dark-mode {

    background: #171417;

}


html.dark-mode body {

    background: #171417;

    color: #f5e9ef;

}


/* HEADER */

html.dark-mode .site-header {

    background: #211c20;

    border-bottom-color: #3b3036;

}


html.dark-mode .brand {

    color: #f5dce8;

}


html.dark-mode .brand small {

    color: #d8b7c6;

}


html.dark-mode .main-nav a {

    color: #f3dce7;

}


html.dark-mode .main-nav a:hover {

    color: #ff9dca;

}


html.dark-mode .link-button {

    color: #f3dce7;

}


html.dark-mode .link-button:hover {

    color: #ff9dca;

}


/* THEME BUTTON DARK */

html.dark-mode .theme-toggle {

    background: #2c252a;

    border-color: #d68daf;

    color: #ffd3e5;

}


html.dark-mode .theme-toggle:hover {

    background: #e8aecf;

    border-color: #e8aecf;

    color: #211c20;

}


/* MAIN */

html.dark-mode main {

    background: #171417;

}


/* CARDS */

html.dark-mode .card,
html.dark-mode .service-card,
html.dark-mode .home-category-card,
html.dark-mode .why-item,
html.dark-mode .appointment-card {

    background: #241f22;

    color: #f5e9ef;

    border-color: #40343a;

}


html.dark-mode .card p,
html.dark-mode .service-card p,
html.dark-mode .home-category-card p,
html.dark-mode .why-item p {

    color: #d7c5cd;

}


/* HEADINGS */

html.dark-mode h1,
html.dark-mode h2,
html.dark-mode h3,
html.dark-mode h4,
html.dark-mode h5,
html.dark-mode h6 {

    color: #f8dce8;

}


/* PARAGRAPHS */

html.dark-mode p {

    color: #e0d1d7;

}


/* FORMS */

html.dark-mode input,
html.dark-mode textarea,
html.dark-mode select {

    background: #211c20;

    color: #f8e9ef;

    border-color: #55434b;

}


html.dark-mode input::placeholder,
html.dark-mode textarea::placeholder {

    color: #a9959e;

}


html.dark-mode input:focus,
html.dark-mode textarea:focus,
html.dark-mode select:focus {

    border-color: #e8aecf;

    box-shadow:
        0 0 0 3px rgba(232, 174, 207, 0.15);

}


/* FLASH */

html.dark-mode .flash {

    color: #f8e9ef;

}


/* FOOTER */

html.dark-mode .site-footer {

    background: #111111;

}


html.dark-mode .footer-contact-item {

    color: #f3d6e5;

}


html.dark-mode .footer-contact-item:hover {

    color: #ff8fc7;

}


html.dark-mode .footer-copyright p {

    color: #e8c9d9;

}


/* CHATBOT */

html.dark-mode .mcares-chat-window {

    background: #211c20;

    border-color: #40343a;

}


html.dark-mode .mcares-chat-messages {

    background: #171417;

}


html.dark-mode .mcares-chat-bubble {

    color: #f5e9ef;

}


html.dark-mode .mcares-chat-quick {

    background: #211c20;

}


html.dark-mode .mcares-chat-quick button {

    background: #2c252a;

    color: #f5dce7;

    border-color: #55434b;

}


html.dark-mode .mcares-chat-form {

    background: #211c20;

    border-top-color: #40343a;

}


html.dark-mode .mcares-chat-form input {

    background: #171417;

    color: #ffffff;

}


/* PROFILE */

html.dark-mode .nav-profile {

    border-color: #d68daf;

}


/* MOBILE */

@media (max-width: 700px) {

    .theme-toggle {

        width: 38px;

        height: 38px;

        margin-left: 5px;

    }

}

</style>




<style>

/* ---------- LIGHT (toggle OFF) ---------- */

html:not(.dark-mode) {

    color-scheme: light;

    --page-bg: #fffafc;
    --surface: #FFFFFF;
    --surface-soft: #fff1f7;
    --input-bg: #FFFFFF;
    --input-border: #ded5d9;

    --heading: #62444D;
    --text: #222222;
    --text-muted: #7d7074;

    --line: rgba(98, 68, 77, .14);

    --header-bg: rgba(255, 255, 255, .94);

    --hero-pink: #f9dce8;
    --hero-light: #fff2d5;

    --success-bg: #eef9ee;
    --success-text: #2d6b38;

    --error-bg: #fff0f3;
    --error-text: #8a3b50;

    --shadow: 0 20px 60px rgba(98, 68, 77, .13);

}

html:not(.dark-mode) body {

    color-scheme: light;

}


/* ---------- DARK (toggle ON) ---------- */

html.dark-mode {

    color-scheme: dark;

    --page-bg: #171315;
    --surface: #211b1e;
    --surface-soft: #2a2024;
    --input-bg: #211b1e;
    --input-border: #4b3b41;

    --heading: #f3c9da;
    --text: #f5edf0;
    --text-muted: #c0adb4;

    --line: rgba(243, 188, 210, .16);

    --header-bg: rgba(28, 23, 26, .94);

    --hero-pink: #382832;
    --hero-light: #352d23;

    --success-bg: #1e3024;
    --success-text: #a8d8af;

    --error-bg: #351f25;
    --error-text: #f0a8b7;

    --shadow: 0 20px 60px rgba(0, 0, 0, .35);

}

html.dark-mode body {

    color-scheme: dark;

}

</style>




<style>

/* BUTTONS */

html:not(.dark-mode) .secondary-button {

    background: rgba(255, 255, 255, .72);

    color: var(--heading);

    border-color: rgba(98, 68, 77, .18);

}

html:not(.dark-mode) .secondary-button:hover {

    background: var(--surface);

    border-color: var(--gold);

}


/* CARDS */

html:not(.dark-mode) .service-card {

    box-shadow: 0 10px 30px rgba(98, 68, 77, .06);

}

html:not(.dark-mode) .panel {

    box-shadow: 0 10px 30px rgba(98, 68, 77, .05);

}


/* PASSWORD EYE BUTTON */

html:not(.dark-mode) .password-toggle:hover,
html:not(.dark-mode) .password-toggle:focus {

    background: var(--surface-soft);

    color: var(--pink);

}


/* CHATBOT */

html:not(.dark-mode) .mcares-chat-window {

    background: #ffffff;

    border-color: rgba(98, 68, 77, 0.12);

    box-shadow:
        0 20px 60px rgba(98, 68, 77, 0.25),
        0 5px 20px rgba(98, 68, 77, 0.12);

}

html:not(.dark-mode) .mcares-chat-messages {

    background: linear-gradient(180deg, #fff8fb 0%, #ffffff 100%);

}

html:not(.dark-mode) .mcares-chat-message.bot .mcares-chat-bubble {

    background: #f3dce7;

    color: #62444d;

}

html:not(.dark-mode) .mcares-chat-quick {

    background: #ffffff;

    border-top-color: rgba(98, 68, 77, 0.08);

}

html:not(.dark-mode) .mcares-chat-quick button {

    background: #fff8fb;

    border-color: #e8aecf;

    color: #62444d;

}

html:not(.dark-mode) .mcares-chat-form {

    background: #ffffff;

    border-top-color: rgba(98, 68, 77, 0.08);

}

html:not(.dark-mode) .mcares-chat-form input {

    background: #ffffff;

    border-color: #e8aecf;

    color: #62444d;

}

html:not(.dark-mode) .mcares-chat-form input::placeholder {

    color: #a88d97;

}



/* PROFILE PAGE (client + admin) */

html:not(.dark-mode) .profile-picture-section {

    background: rgba(233, 30, 140, 0.06);

}

html:not(.dark-mode) .profile-picture-info h3 {

    color: var(--heading);

}

html:not(.dark-mode) .profile-picture-info p,
html:not(.dark-mode) .profile-picture-info small {

    color: var(--text-muted);

}

html:not(.dark-mode) .nav-profile {

    border-color: #ffffff;

}

html:not(.dark-mode) .nav-profile:hover {

    border-color: #e91e8c;

}


/* HERO "Feel your best." LINE */

html:not(.dark-mode) .hero-copy h1 em,
html:not(.dark-mode) .hero-text-slide h1 em {

    color: #9a6478;

}



/* PROFILE "Choose Picture" BUTTON
   (".form-stack label" was making its text dark and stretching it) */

.form-stack label.profile-upload-button {

    display: inline-block;

    width: auto;

    color: #ffffff;

}



/* MAIN BUTTONS IN LIGHT MODE
   (Save Changes, + New appointment, Send appointment request,
    Book Now, Book an Appointment ... same look as "Explore All Services") */

html:not(.dark-mode) .primary-button {

    background: rgba(255, 255, 255, .72);

    color: var(--heading);

    border-color: rgba(98, 68, 77, .18);

}

html:not(.dark-mode) .primary-button:hover {

    background: var(--surface);

    color: var(--heading);

    border-color: var(--gold);

}

</style>




<style>

/* MEET THE FOUNDER */

html.dark-mode .founder-section {

    background: linear-gradient(110deg, #1f191d, #251d22, #1d171b);

    color: #f5e9ef;

}

html.dark-mode .founder-photo-frame {

    border-color: rgba(243, 188, 210, .25);

    background: #2a2024;

}

html.dark-mode .founder-photo-decoration {

    border-color: #8a5a72;

}

html.dark-mode .founder-signature {

    border-top-color: #5a4350;

}

html.dark-mode .founder-signature p,
html.dark-mode .founder-signature h3 {

    color: #e8d3dc;

}

html.dark-mode .founder-quote {

    color: #f08fb5;

}

html.dark-mode .founder-message {

    background: rgba(255, 255, 255, .04);

    border-color: #4a3a41;

}

html.dark-mode .founder-sign,
html.dark-mode .founder-sign span {

    color: #f08fb5;

}


/* AWARDS & ACHIEVEMENTS */

html.dark-mode .achievements-section {

    background: linear-gradient(110deg, #1c171a, #231c20, #1b1619);

    color: #f5e9ef;

}

html.dark-mode .achievement-art {

    background:
        radial-gradient(circle at top, #3a2f2a, transparent 55%),
        linear-gradient(145deg, #2c2327, #221c20);

    border-color: #4f3f45;

}

html.dark-mode .achievement-art h2,
html.dark-mode .achievement-content h2 {

    color: #f8dce8;

}

html.dark-mode .achievement-small-label {

    color: #d9b48a;

}

html.dark-mode .achievement-seal {

    color: #e0bd78;

    background: rgba(255, 255, 255, .05);

}

html.dark-mode .achievement-intro,
html.dark-mode .achievement-list li {

    color: #e0d1d7;

}

html.dark-mode .achievement-motto h3 {

    color: #f08fb5;

}

html.dark-mode .achievement-motto > span {

    background: #6a4a5a;

}


/* SERVICES */

html.dark-mode .home-services-section {

    background: linear-gradient(180deg, #1b161a, #221b20);

}

html.dark-mode .home-services-section .section-heading p {

    color: #cdb9c1;

}

html.dark-mode .home-services-section .section-heading h2,
html.dark-mode .why-mcares-section .section-heading h2 {

    color: #f8dce8;

}

html.dark-mode .home-category-card h3 {

    color: #f8dce8;

}

html.dark-mode .secondary-button {

    background: rgba(255, 255, 255, .06);

    color: #f8dce8;

    border-color: rgba(243, 188, 210, .4);

}

html.dark-mode .secondary-button:hover {

    background: #2c252a;

    border-color: #e8aecf;

}


/* WHY CHOOSE US */

html.dark-mode .why-mcares-section {

    background: linear-gradient(110deg, #201a1e, #271f24, #1e181c);

    color: #f5e9ef;

}

html.dark-mode .why-item {

    background: transparent;

    border-color: #4f3b46;

}

html.dark-mode .why-item h3 {

    color: #f8dce8;

}

html.dark-mode .why-item p {

    color: #d7c5cd;

}


/* PHOTO SLIDESHOW ("A Look Inside M. Cares") */

html.dark-mode .mcares-slideshow-section {

    background: linear-gradient(180deg, #1f181d 0%, #171417 100%);

}

html.dark-mode .mcares-slideshow-header h2 {

    color: #f8dce8;

}

html.dark-mode .mcares-slideshow-header p,
html.dark-mode .mcares-slide-caption {

    color: #d7c5cd;

}



/* PROFILE PAGE (client + admin) */

html.dark-mode .profile-picture-section {

    background: rgba(255, 175, 241, 0.08);

}

html.dark-mode .profile-picture-info h3 {

    color: #ffffff;

}

html.dark-mode .profile-picture-info p,
html.dark-mode .profile-picture-info small {

    color: #dddddd;

}

html.dark-mode .nav-profile {

    border-color: #444444;

}

html.dark-mode .nav-profile:hover {

    border-color: #ffaff1;

}


/* HERO "Feel your best." LINE */

html.dark-mode .hero-copy h1 em,
html.dark-mode .hero-text-slide h1 em {

    color: #d99ab4;

}

</style>




<style>

/* ---------- ANNOUNCEMENT BAR ---------- */

.promo-bar {

    position: relative;

    padding: 9px 46px;

    background: linear-gradient(110deg, #70475e, #b46e91, #70475e);

    color: #ffffff;

    text-align: center;

    font-size: 13px;

    font-weight: 600;

    line-height: 1.4;

}

.promo-bar a {

    color: #ffffff;

    text-decoration: none;

}

.promo-bar a:hover {

    text-decoration: underline;

}

.promo-bar strong {

    color: #ffe9a8;

}

.promo-bar-close {

    position: absolute;

    top: 50%;

    right: 12px;

    transform: translateY(-50%);

    padding: 2px 8px;

    border: 0;

    background: transparent;

    color: #ffffff;

    font-size: 22px;

    line-height: 1;

    cursor: pointer;

    opacity: .8;

}

.promo-bar-close:hover {

    opacity: 1;

}

.promo-bar.is-hidden {

    display: none;

}


/* ---------- PROMO CARD ---------- */

.promo-section {

    padding: 55px 0;

    background: linear-gradient(180deg, #fff8fb, #fff1f6);

}

.promo-section.is-compact {

    padding: 0;

    margin: 0 0 28px;

    background: none;

}

.promo-card {

    max-width: 1000px;

    margin: 0 auto;

    overflow: hidden;

    border: 1px solid var(--line);

    border-radius: 22px;

    background: var(--surface);

    box-shadow: var(--shadow);

}

.promo-poster {

    display: block;

}

.promo-poster img {

    display: block;

    width: 100%;

    height: auto;

}

.promo-caption {

    display: flex;

    align-items: center;

    justify-content: space-between;

    flex-wrap: wrap;

    gap: 16px;

    padding: 18px 24px;

}

.promo-caption h3 {

    margin: 4px 0 0;

    color: var(--heading);

    font-family: 'Playfair Display', Georgia, serif;

    font-size: 20px;

    line-height: 1.3;

}

.promo-cta {

    display: inline-block;

    padding: 11px 24px;

    border-radius: 999px;

    background: #e91e8c;

    color: #ffffff !important;

    font-size: 14px;

    font-weight: 700;

    text-decoration: none;

    transition: .2s;

}

.promo-cta:hover {

    background: #c4177a;

    transform: translateY(-1px);

}

.promo-live {

    padding: 6px 12px;

    border-radius: 999px;

    background: #e4f7e9;

    color: #2d6b38;

    font-size: 12px;

    font-weight: 700;

}

html.dark-mode .promo-section {

    background: linear-gradient(180deg, #1b161a, #221b20);

}

html.dark-mode .promo-section.is-compact {

    background: none;

}

html.dark-mode .promo-live {

    background: #1e3024;

    color: #a8d8af;

}

@media (max-width: 600px) {

    .promo-bar {

        padding: 9px 38px 9px 14px;

        font-size: 12px;

    }

    .promo-caption {

        flex-direction: column;

        text-align: center;

    }

    .promo-section {

        padding: 40px 0;

    }

}

</style>




<script>

document.addEventListener('DOMContentLoaded', function () {

    const bar   = document.getElementById('promo-bar');
    const close = document.getElementById('promo-bar-close');

    if (!bar || !close) {
        return;
    }

    try {
        if (sessionStorage.getItem('mcares-promo-bar') === 'closed') {
            bar.classList.add('is-hidden');
        }
    } catch (e) {}

    close.addEventListener('click', function () {

        bar.classList.add('is-hidden');

        try {
            sessionStorage.setItem('mcares-promo-bar', 'closed');
        } catch (e) {}

    });

});

</script>




<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {

        const themeToggle =
            document.getElementById('theme-toggle');

        const themeIcon =
            document.getElementById('theme-icon');


        /*
        |--------------------------------------------------------------------------
        | Check if button exists
        |--------------------------------------------------------------------------
        */

        if (!themeToggle || !themeIcon) {

            return;

        }


        /*
        |--------------------------------------------------------------------------
        | Update button
        |--------------------------------------------------------------------------
        */

        function updateThemeButton() {

            const isDark =
                document.documentElement.classList.contains(
                    'dark-mode'
                );


            if (isDark) {

                themeIcon.textContent = '☀️';

                themeToggle.setAttribute(
                    'aria-label',
                    'Switch to light mode'
                );

                themeToggle.setAttribute(
                    'title',
                    'Switch to light mode'
                );

            } else {

                themeIcon.textContent = '🌙';

                themeToggle.setAttribute(
                    'aria-label',
                    'Switch to dark mode'
                );

                themeToggle.setAttribute(
                    'title',
                    'Switch to dark mode'
                );

            }

        }


        /*
        |--------------------------------------------------------------------------
        | Initial button state
        |--------------------------------------------------------------------------
        */

        updateThemeButton();


        /*
        |--------------------------------------------------------------------------
        | CLICK
        |--------------------------------------------------------------------------
        */

        themeToggle.addEventListener(
            'click',
            function () {

                const isDark =
                    document.documentElement.classList.toggle(
                        'dark-mode'
                    );


                /*
                |--------------------------------------------------------------------------
                | Save theme
                |--------------------------------------------------------------------------
                */

                localStorage.setItem(
                    'mcares-theme',
                    isDark
                        ? 'dark'
                        : 'light'
                );


                /*
                |--------------------------------------------------------------------------
                | Update icon
                |--------------------------------------------------------------------------
                */

                updateThemeButton();

            }
        );

    }
);

</script>


</body>

</html><?php /**PATH C:\Users\Regis D\Desktop\Beauty-Aesthetic-Appointment-System\resources\views/layouts/app.blade.php ENDPATH**/ ?>