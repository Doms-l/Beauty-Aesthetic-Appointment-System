@php
    $compact = $compact ?? false;
    $admin   = $admin   ?? false;

    $promo = \App\Models\Promo::current();

    $promoLink = auth()->check()
        ? (auth()->user()->isClient()
            ? route('client.appointments.create')
            : route('home'))
        : route('register');
@endphp

@if($promo->is_active)
<section class="promo-section {{ $compact ? 'is-compact' : '' }}">

    @unless($compact)<div class="container">@endunless

        <div class="promo-card">

            <a class="promo-poster" href="{{ $admin ? route('home') : $promoLink }}">

                <img
                    src="{{ $promo->image_url }}"
                    alt="{{ $promo->title }}"
                    loading="lazy"
                >

            </a>

            <div class="promo-caption">

                <div>

                    <span class="eyebrow">
                        {{ $admin ? 'ACTIVE PROMOTION' : 'LIMITED-TIME PROMO' }}
                    </span>

                    <h3>{{ $promo->title }}</h3>

                </div>

                @if($admin)

                    <span class="promo-live">
                        &#9679; Showing on the website and client pages
                    </span>

                @else

                    <a class="promo-cta" href="{{ $promoLink }}">
                        Book this promo
                    </a>

                @endif

            </div>

        </div>

    @unless($compact)</div>@endunless

</section>
@endif
