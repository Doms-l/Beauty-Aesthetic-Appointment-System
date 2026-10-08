@php
    $compact = $compact ?? false;
    $admin   = $admin   ?? false;

    $promoLink = auth()->check()
        ? (auth()->user()->isClient()
            ? route('client.appointments.create')
            : route('home'))
        : route('register');
@endphp

<section class="promo-section {{ $compact ? 'is-compact' : '' }}">

    @unless($compact)<div class="container">@endunless

        <div class="promo-card">

            <a class="promo-poster" href="{{ $admin ? route('home') : $promoLink }}">

                <img
                    src="{{ asset('images/promo.jpg') }}"
                    alt="Macayla Cares Retouch/Recolor Microbrows promo: P999 with free lashes"
                    loading="lazy"
                >

            </a>

            <div class="promo-caption">

                <div>

                    <span class="eyebrow">
                        {{ $admin ? 'ACTIVE PROMOTION' : 'LIMITED-TIME PROMO' }}
                    </span>

                    <h3>
                        Retouch/Recolor Microbrows
                        &mdash; &#8369;999 with FREE Lashes
                    </h3>

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
