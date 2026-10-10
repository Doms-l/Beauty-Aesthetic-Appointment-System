@extends('layouts.app')

@section('title', 'Services | M. Cares Beauty Services')

@section('content')

@php
    $norm = fn ($s) => preg_replace('/[^a-z0-9]/', '', strtolower($s));

    // Descriptions and photos now come from App\Support\ServiceCatalog
    // and the Service model (so the admin page shows the same ones).
    $encodePath = fn ($path) =>
        implode('/', array_map('rawurlencode', explode('/', $path)));
@endphp


{{-- PAGE HERO --}}
<section class="page-hero">
    <div class="container">
        <span class="eyebrow">M. CARES BEAUTY SERVICES</span>
        <h1>Our Beauty Services</h1>
        <p>
            Explore the beauty treatments and services available
            at M. Cares Beauty Services.
        </p>
    </div>
</section>


{{-- SERVICES GRID --}}
<section class="section">
    <div class="container">

        @forelse($groupedServices as $category => $categoryServices)

            <div class="services-category">

                <div class="section-heading">
                    <span class="eyebrow">M. CARES</span>
                    <h2>{{ $category }}</h2>
                </div>

                <div class="card-grid service-list-grid">

                    @foreach($categoryServices as $service)

                        @php
                            $serviceImage = $service->image_path;

                            $bookingUrl = route(
                                'client.appointments.create',
                                ['service' => $service->id]
                            );

                            $serviceDescription = $service->effective_description
                                ?: 'Description is not available yet.';
                        @endphp

                        <article class="service-card service-page-card">

                            {{-- CLICKABLE SERVICE IMAGE --}}
                            @if($serviceImage)
                                <button
                                    type="button"
                                    class="service-image-trigger"
                                    aria-label="View details for {{ $service->name }}"
                                    data-service-name="{{ $service->name }}"
                                    data-service-category="{{ $category }}"
                                    data-service-description="{{ $serviceDescription }}"
                                    data-service-price="{{ $service->display_price }}"
                                    data-service-image="{{ asset($encodePath($serviceImage)) }}"
                                    data-service-book="{{ $bookingUrl }}"
                                >
                                    <img
                                        src="{{ asset($encodePath($serviceImage)) }}"
                                        alt="{{ $service->name }}"
                                        loading="lazy"
                                    >
                                    <span class="image-view-hint">View Details</span>
                                </button>
                            @endif

                            <h3>{{ $service->name }}</h3>

                            @if($service->description)
                                <p>{{ $service->description }}</p>
                            @else
                                <p>Available at M. Cares Beauty Services.</p>
                            @endif

                            <div class="service-meta">
                                <span>Price</span>
                                <strong>{{ $service->display_price }}</strong>
                            </div>

                            @auth
                                @if(auth()->user()->isClient())
                                    <div class="service-card-action">
                                        <a
                                            href="{{ $bookingUrl }}"
                                            class="primary-button"
                                        >
                                            Book Now
                                        </a>
                                    </div>
                                @endif
                            @else
                                <div class="service-card-action">
                                    <a
                                        href="{{ route('login') }}"
                                        class="primary-button"
                                    >
                                        Login to Book
                                    </a>
                                </div>
                            @endauth

                        </article>

                    @endforeach

                </div>
            </div>

        @empty

            <div class="empty-state">
                <h3>No services available</h3>
                <p>
                    Our services are currently being updated.
                    Please check again later.
                </p>
            </div>

        @endforelse

    </div>
</section>


{{-- BOOKING CTA --}}
<section class="section compact soft-section">
    <div class="container">
        <div class="panel spotlight">
            <span class="eyebrow">READY TO BOOK?</span>
            <h2>Choose your treatment and request an appointment.</h2>
            <p>
                Browse our available services and select the treatment
                that fits your beauty and care needs.
            </p>

            @auth
                @if(auth()->user()->isClient())
                    <a
                        href="{{ route('client.appointments.create') }}"
                        class="primary-button"
                    >
                        Book an Appointment
                    </a>
                @endif
            @else
                <a href="{{ route('register') }}" class="primary-button">
                    Create an Account
                </a>
            @endauth
        </div>
    </div>
</section>


{{-- FLOATING SERVICE DETAIL POPUP --}}
<div
    class="service-modal"
    id="serviceModal"
    aria-hidden="true"
>
    <div
        class="service-modal-backdrop"
        data-close-service-modal
    ></div>

    <div
        class="service-modal-dialog"
        role="dialog"
        aria-modal="true"
        aria-labelledby="modalServiceName"
    >

        <button
            type="button"
            class="service-modal-close"
            id="closeServiceModal"
            aria-label="Close service details"
        >
            &times;
        </button>

        <div class="service-modal-content">

            {{-- LEFT SIDE --}}
            <div class="service-modal-info">

                <span
                    class="service-modal-category"
                    id="modalServiceCategory"
                >
                    FACIAL SERVICES
                </span>

                <h2 id="modalServiceName">
                    Service Name
                </h2>

                <p
                    class="service-modal-description"
                    id="modalServiceDescription"
                >
                    Service description will appear here.
                </p>

                <div class="service-modal-price">
                    <span>Price</span>
                    <strong id="modalServicePrice">—</strong>
                </div>

                <div class="service-modal-actions">

                    @auth
                        @if(auth()->user()->isClient())
                            <a
                                href="{{ route('client.appointments.create') }}"
                                class="primary-button service-modal-book"
                                id="modalBookButton"
                            >
                                Book Now
                            </a>
                        @endif
                    @else
                        <a
                            href="{{ route('login') }}"
                            class="primary-button service-modal-book"
                            id="modalBookButton"
                        >
                            Login to Book
                        </a>
                    @endauth

                </div>

            </div>

            {{-- RIGHT SIDE: ENLARGED IMAGE --}}
            <div class="service-modal-image-area">
                <img
                    src=""
                    alt=""
                    id="modalServiceImage"
                >
            </div>

        </div>
    </div>
</div>


{{-- POPUP STYLES --}}
<style>
    .service-image-trigger {
        position: relative;
        display: block;
        width: 100%;
        height: 200px;
        padding: 0;
        margin: 0 0 14px;
        overflow: hidden;
        border: 0;
        border-radius: 12px;
        background: #f4e8ed;
        cursor: pointer;
        text-align: left;
    }

    .service-image-trigger img {
        display: block;
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform .35s ease;
    }

    .service-image-trigger:hover img {
        transform: scale(1.05);
    }

    .image-view-hint {
        position: absolute;
        right: 10px;
        bottom: 10px;
        padding: 7px 12px;
        border-radius: 20px;
        background: rgba(35, 28, 32, .82);
        color: #fff;
        font-size: 12px;
        opacity: 0;
        transform: translateY(4px);
        transition: .2s ease;
    }

    .service-image-trigger:hover .image-view-hint,
    .service-image-trigger:focus-visible .image-view-hint {
        opacity: 1;
        transform: translateY(0);
    }

    .service-modal {
        position: fixed;
        inset: 0;
        z-index: 99999;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 24px;
    }

    .service-modal.is-open {
        display: flex;
        animation: modalFadeIn .2s ease;
    }

    .service-modal-backdrop {
        position: absolute;
        inset: 0;
        background: rgba(10, 8, 10, .78);
        backdrop-filter: blur(5px);
        -webkit-backdrop-filter: blur(5px);
    }

    .service-modal-dialog {
        position: relative;
        z-index: 1;
        width: min(960px, 100%);
        max-height: calc(100vh - 48px);
        overflow: auto;
        border: 1px solid #493942;
        border-radius: 22px;
        background: #241f22;
        color: #f7edf2;
        box-shadow: 0 25px 90px rgba(0, 0, 0, .5);
        animation: modalRise .25s ease;
    }

    .service-modal-close {
        position: absolute;
        top: 13px;
        right: 15px;
        z-index: 3;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border: 1px solid rgba(255, 255, 255, .3);
        border-radius: 50%;
        background: rgba(30, 25, 28, .75);
        color: white;
        font-size: 29px;
        line-height: 1;
        cursor: pointer;
        transition: background .2s ease;
    }

    .service-modal-close:hover {
        background: #e91e8c;
    }

    .service-modal-content {
        display: grid;
        grid-template-columns: .9fr 1.1fr;
        min-height: 480px;
    }

    .service-modal-info {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        padding: 55px 38px 32px;
    }

    .service-modal-category {
        margin-bottom: 15px;
        color: #ed9ac0;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 1.6px;
        line-height: 1.6;
        text-transform: uppercase;
    }

    .service-modal-info h2 {
        margin: 0 0 18px;
        color: #f7dbe8;
        font-family: Georgia, "Times New Roman", serif;
        font-size: clamp(27px, 3vw, 36px);
        line-height: 1.2;
        overflow-wrap: anywhere;
    }

    .service-modal-description {
        margin: 0;
        color: #ded1d7;
        font-size: 15px;
        line-height: 1.8;
        white-space: pre-line;
    }

    .service-modal-price {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        width: 100%;
        margin-top: auto;
        padding-top: 22px;
        border-top: 1px solid #493942;
    }

    .service-modal-price span {
        color: #d4bdc8;
        font-size: 14px;
    }

    .service-modal-price strong {
        color: #f2a5c7;
        font-size: 24px;
        font-weight: 700;
        text-align: right;
    }

    .service-modal-actions {
        display: flex;
        justify-content: flex-end;
        width: 100%;
        margin-top: 24px;
    }

    .service-modal-book {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 145px;
        padding: 13px 24px;
        border-radius: 30px;
        text-align: center;
    }

    .service-modal-image-area {
        position: relative;
        min-height: 480px;
        overflow: hidden;
        background: #191518;
    }

    .service-modal-image-area img {
        display: block;
        width: 100%;
        height: 100%;
        min-height: 480px;
        max-height: 650px;
        object-fit: cover;
    }

    @keyframes modalFadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes modalRise {
        from {
            opacity: 0;
            transform: translateY(12px) scale(.99);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    html:not(.dark-mode) .service-modal-dialog {
        background: #fff8fb;
        border-color: #ead7df;
        color: #62444d;
    }

    html:not(.dark-mode) .service-modal-info h2 {
        color: #62444d;
    }

    html:not(.dark-mode) .service-modal-description {
        color: #6f6067;
    }

    html:not(.dark-mode) .service-modal-price {
        border-top-color: #ead7df;
    }

    html:not(.dark-mode) .service-modal-price span {
        color: #796b72;
    }

    @media (max-width: 700px) {
        .service-modal {
            padding: 12px;
        }

        .service-modal-dialog {
            max-height: calc(100vh - 24px);
            border-radius: 17px;
        }

        .service-modal-content {
            grid-template-columns: 1fr;
        }

        .service-modal-image-area {
            grid-row: 1;
            min-height: 240px;
            height: 35vh;
            max-height: 330px;
        }

        .service-modal-image-area img {
            min-height: 240px;
            max-height: 330px;
            height: 100%;
        }

        .service-modal-info {
            grid-row: 2;
            padding: 25px 23px 24px;
        }

        .service-modal-info h2 {
            font-size: 28px;
            padding-right: 12px;
        }

        .service-modal-description {
            font-size: 14px;
        }

        .service-modal-close {
            top: 10px;
            right: 10px;
        }

        .service-modal-actions {
            margin-top: 22px;
        }

        .service-modal-book {
            width: 100%;
        }

        .image-view-hint {
            opacity: 1;
            transform: none;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .service-modal,
        .service-modal-dialog,
        .service-image-trigger img {
            animation: none;
            transition: none;
        }
    }
</style>


{{-- POPUP JAVASCRIPT --}}
<script>
(function () {
    function initializeServiceModal() {
        const modal = document.getElementById('serviceModal');

        if (!modal || modal.dataset.initialized === 'true') {
            return;
        }

        modal.dataset.initialized = 'true';

        const closeButton = document.getElementById('closeServiceModal');
        const categoryText = document.getElementById('modalServiceCategory');
        const nameText = document.getElementById('modalServiceName');
        const descriptionText = document.getElementById('modalServiceDescription');
        const priceText = document.getElementById('modalServicePrice');
        const imageElement = document.getElementById('modalServiceImage');
        const bookButton = document.getElementById('modalBookButton');

        let previousFocus = null;

        function openModal(button) {
            previousFocus = button;

            categoryText.textContent = button.dataset.serviceCategory || '';
            nameText.textContent = button.dataset.serviceName || '';
            descriptionText.textContent = button.dataset.serviceDescription || '';
            priceText.textContent = button.dataset.servicePrice || '';

            imageElement.src = button.dataset.serviceImage || '';
            imageElement.alt = button.dataset.serviceName || '';

            if (bookButton && button.dataset.serviceBook) {
                bookButton.href = button.dataset.serviceBook;
            }

            modal.classList.add('is-open');
            modal.setAttribute('aria-hidden', 'false');
            document.body.style.overflow = 'hidden';

            closeButton.focus();
        }

        function closeModal() {
            modal.classList.remove('is-open');
            modal.setAttribute('aria-hidden', 'true');
            document.body.style.overflow = '';

            if (previousFocus) {
                previousFocus.focus();
            }
        }

        document.querySelectorAll('.service-image-trigger').forEach(function (button) {
            button.addEventListener('click', function () {
                openModal(button);
            });
        });

        closeButton.addEventListener('click', closeModal);

        modal.querySelectorAll('[data-close-service-modal]').forEach(function (element) {
            element.addEventListener('click', closeModal);
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && modal.classList.contains('is-open')) {
                closeModal();
            }
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initializeServiceModal);
    } else {
        initializeServiceModal();
    }
})();
</script>

@endsection
