@extends('layouts.app')

@section('title', 'Services | M. Cares Beauty Services')

@section('content')

{{-- =========================================================
     SERVICES HERO
     ========================================================= --}}
<section class="page-hero">
    <div class="container">

        <span class="eyebrow">
            M. CARES BEAUTY SERVICES
        </span>

        <h1>
            Our Beauty Services
        </h1>

        <p>
            Explore the beauty treatments and services available
            at M. Cares Beauty Services.
        </p>

    </div>
</section>


{{-- =========================================================
     SERVICES
     ========================================================= --}}
<section class="section">

    <div class="container">

        @forelse($groupedServices as $category => $categoryServices)

            <div class="services-category">

                <div class="section-heading">

                    <span class="eyebrow">
                        M. CARES
                    </span>

                    <h2>
                        {{ $category }}
                    </h2>

                </div>


                <div class="card-grid service-list-grid">

                    @foreach($categoryServices as $service)

                        <article class="service-card service-page-card">

                            <h3>
                                {{ $service->name }}
                            </h3>

                            @if($service->description)
                                <p>
                                    {{ $service->description }}
                                </p>
                            @else
                                <p>
                                    Available at M. Cares Beauty Services.
                                </p>
                            @endif

                            <div class="service-meta">

                                <span>
                                    Price
                                </span>

                                <strong>
                                    {{ $service->display_price }}
                                </strong>

                            </div>


                            @auth

                                @if(auth()->user()->isClient())

                                    <div class="service-card-action">

                                        <a
                                            href="{{ route('client.appointments.create', ['service' => $service->id]) }}"
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

                <h3>
                    No services available
                </h3>

                <p>
                    Our services are currently being updated.
                    Please check again later.
                </p>

            </div>

        @endforelse

    </div>

</section>


{{-- =========================================================
     BOOKING CTA
     ========================================================= --}}
<section class="section compact soft-section">

    <div class="container">

        <div class="panel spotlight">

            <span class="eyebrow">
                READY TO BOOK?
            </span>

            <h2>
                Choose your treatment and request an appointment.
            </h2>

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

                <a
                    href="{{ route('register') }}"
                    class="primary-button"
                >
                    Create an Account
                </a>

            @endauth

        </div>

    </div>

</section>

@endsection