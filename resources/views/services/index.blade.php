@extends('layouts.app')

@section('title', 'Services | M. Cares Beauty Services')

@section('content')

{{-- =========================================================
     AUTO IMAGE MATCHER
     Scans the image folders once and matches each service
     name to its picture automatically.
     ========================================================= --}}
@php
    $norm = fn ($s) => preg_replace('/[^a-z0-9]/', '', strtolower($s));

    // Build an index of every image in the three folders
    $imageIndex = [];

    foreach (['Facial Services', 'Lash and Brows Services', 'Other Services'] as $folder) {
        $dir = public_path('images/' . $folder);

        if (is_dir($dir)) {
            foreach (\Illuminate\Support\Facades\File::files($dir) as $file) {
                $key = $norm($file->getFilenameWithoutExtension());
                $imageIndex[$key] = 'images/' . $folder . '/' . $file->getFilename();
            }
        }
    }

    // Service name on the site => image file name (without extension)
    // Add a line here whenever a service name differs from its file name.
    $aliases = [
        'Brow Lamination W/Tint'  => 'Brow Lamination with Tint',
        'Upper Lip Wax'           => 'Upper Lip Removal',
        'Melano Out Melasma Meso' => 'Melasma Treatment',
    ];

    $aliasIndex = [];
    foreach ($aliases as $serviceName => $fileName) {
        $aliasIndex[$norm($serviceName)] = $norm($fileName);
    }

    $findServiceImage = function ($name) use ($imageIndex, $aliasIndex, $norm) {
        $key = $norm($name);

        // 0. Manual alias
        if (isset($aliasIndex[$key], $imageIndex[$aliasIndex[$key]])) {
            return $imageIndex[$aliasIndex[$key]];
        }

        // 1. Exact match (ignoring case, spaces and symbols)
        if (isset($imageIndex[$key])) {
            return $imageIndex[$key];
        }

        // 2. Fallback: longest file name that the service name starts with (or vice versa)
        $best = null;
        $bestLen = 0;

        foreach ($imageIndex as $k => $path) {
            $match = (strlen($k) >= 5 && str_starts_with($key, $k))
                  || (strlen($key) >= 6 && str_starts_with($k, $key));

            if ($match && strlen($k) > $bestLen) {
                $best = $path;
                $bestLen = strlen($k);
            }
        }

        return $best;
    };

    // Safely encode a path for a URL (handles spaces, +, parentheses, etc.)
    $encodePath = fn ($path) => implode('/', array_map('rawurlencode', explode('/', $path)));
@endphp


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

                            {{-- SERVICE IMAGE --}}
                            @php
                                $serviceImage = $findServiceImage($service->name);
                            @endphp

                            @if($serviceImage)
                                <div class="service-card-image"
                                     style="width:100%; height:160px; overflow:hidden; border-radius:12px; margin-bottom:14px;">
                                    <img
                                        src="{{ asset($encodePath($serviceImage)) }}"
                                        alt="{{ $service->name }}"
                                        loading="lazy"
                                        style="display:block; width:100%; height:100%; max-width:100%; object-fit:cover;"
                                    >
                                </div>
                            @endif

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