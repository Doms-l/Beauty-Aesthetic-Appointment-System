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

    <?php echo app('Illuminate\Foundation\Vite')->reactRefresh(); ?>

    <?php echo app('Illuminate\Foundation\Vite')([
        'resources/css/app.css',
        'resources/js/app.jsx'
    ]); ?>

</head>

<body>



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
                                src="<?php echo e(asset('storage/' . auth()->user()->profile_picture)); ?>"
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
                src="<?php echo e(asset('storage/' . auth()->user()->profile_picture)); ?>"
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

        </div>


        

        <form
            id="mcares-chat-form"
            class="mcares-chat-form"
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

    <div class="container footer-grid">

        <div>

            <h3>
                M. CARES
            </h3>

            <p>
                Beauty, care, and confidence in one place.
            </p>

        </div>


        <div>

            <p>
                Web-Based Aesthetic Clinic Appointment and
                Management System
            </p>

            <p>
                © <?php echo e(date('Y')); ?>

                M. Cares Beauty Services
            </p>

        </div>

    </div>

</footer>


</body>

</html><?php /**PATH C:\Users\Regis D\Desktop\Beauty-Aesthetic-Appointment-System\resources\views/layouts/app.blade.php ENDPATH**/ ?>