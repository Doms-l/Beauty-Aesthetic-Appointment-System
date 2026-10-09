@extends('layouts.app')

@section('title', 'Services | M. Cares Beauty Services')

@section('content')

@php
    $norm = fn ($s) => preg_replace('/[^a-z0-9]/', '', strtolower($s));

    /*
    |--------------------------------------------------------------------------
    | Service descriptions
    |--------------------------------------------------------------------------
    | Descriptions are matched using normalized service names.
    | If a service is not listed here, its database description is used.
    */
    $serviceDescriptions = [
        'basicfacial' => 'A Basic Facial is a gentle skincare treatment designed to cleanse and refresh the skin. It typically includes cleansing, exfoliation, and removal of surface impurities. The treatment helps remove dead skin cells and excess oil that can make the skin look dull. It leaves the skin feeling cleaner, smoother, and more refreshed.',

        'microdermabrasiondiamondpeel' => 'Microdermabrasion or Diamond Peel is a non-invasive exfoliation treatment that removes dead skin cells from the surface of the skin. It uses a specialized diamond-tipped device to gently exfoliate the skin. The treatment can help improve skin texture, smoothness, and overall appearance. It may also help make the skin look brighter and more even.',

        'acnetreatment' => 'Acne Treatment is a specialized facial treatment designed for acne-prone skin. It focuses on managing excess oil, clogged pores, blackheads, and blemishes. The treatment may include cleansing, exfoliation, extraction, and the application of products suitable for acne-prone skin. It helps promote a cleaner and healthier-looking complexion.',

        'hydrafacial' => 'A Hydra Facial is a multi-step facial treatment that cleanses, exfoliates, extracts impurities, and hydrates the skin. It uses specialized equipment to remove surface buildup and impurities from the pores. The treatment also delivers hydrating and nourishing ingredients to the skin. It leaves the skin looking smoother, fresher, and more hydrated.',

        'antiagingfacial' => 'An Anti-Aging Facial is designed to improve the appearance of visible signs of skin aging. It commonly focuses on concerns such as fine lines, dullness, uneven texture, and loss of firmness. The treatment may involve cleansing, exfoliation, massage, and the application of skincare products. It helps the skin appear smoother, brighter, and more refreshed.',

        'melasmatreatment' => 'Melasma Treatment is a skincare procedure focused on improving the appearance of uneven pigmentation and dark patches. It is commonly used for areas of the face affected by melasma. Depending on the clinic protocol, the treatment may involve topical products, exfoliation, or specialized procedures. The goal is to promote a more even-looking complexion and improve the appearance of pigmentation.',

        'picocarbonlasertreatment' => 'Pico Carbon Laser Treatment is a laser-based cosmetic procedure that uses short pulses of laser energy. It is commonly used to improve the appearance of skin tone, texture, and pigmentation. The treatment may also help remove surface impurities and promote a smoother-looking complexion. Results and the number of treatments needed may vary depending on the individual skin condition.',

        'oxygeneofacial' => 'Oxygeneo Facial is a cosmetic facial treatment designed to exfoliate and refresh the skin. It combines exfoliation with the delivery of nourishing ingredients to the skin surface. The treatment helps remove dead skin cells and improve the appearance of skin texture. It can leave the skin looking smoother, cleaner, and more refreshed.',

        'koreanbbglowbbblush' => 'Korean BB Glow + BB Blush is a cosmetic facial treatment designed to create a more even-looking and radiant complexion. It uses specialized products to give the skin a naturally tinted and glowing appearance. BB Glow focuses on improving the overall appearance of the complexion, while BB Blush adds a subtle blush-like effect. The treatment is intended to create a fresh and polished appearance.',

        'freestemcellfacial' => 'Free Stemcell Facial is a complimentary facial treatment focused on cleansing, refreshing, and nourishing the skin. It may include basic facial steps such as cleansing, exfoliation, and product application. The treatment is intended to provide a relaxing skincare experience while improving the skin overall appearance. Specific products and procedures may vary according to the clinic protocol.',

        'lashextension' => 'Lash Extension is a beauty treatment in which individual artificial lash extensions are attached to the natural eyelashes. It is designed to make the lashes appear longer, fuller, and more defined. Different lengths, thicknesses, and styles may be selected depending on the desired look. Proper application and maintenance help achieve a natural and polished appearance.',

        'lashlift' => 'Lashlift is a treatment that curls and lifts the natural eyelashes. It creates a more open and defined appearance around the eyes without adding artificial lashes. The treatment can make the natural lashes appear longer and more noticeable. Results are temporary and gradually fade as the natural lashes grow and shed.',

        'browtint' => 'Brow Tint is a semi-permanent cosmetic treatment that adds color to the eyebrow hairs. It helps enhance the natural color and definition of the brows. The tint can make the eyebrows appear fuller and more noticeable. The intensity and shade of the tint may be selected based on the client desired appearance.',

        'browlaminationwithtint' => 'Brow Lamination with Tint is a treatment that shapes and sets the eyebrow hairs while adding color. It helps create a fuller, more structured, and defined brow appearance. The brow hairs are positioned in a desired direction and treated with specialized products. The addition of tint enhances the color and overall definition of the eyebrows.',

        'microblading' => 'Microblading is a semi-permanent cosmetic eyebrow technique that creates fine, hair-like strokes. A specialized tool is used to deposit pigment into the superficial layers of the skin. The technique is designed to enhance the shape and appearance of the eyebrows. The final result can create a more defined and naturally styled brow appearance.',

        'microbrowsretouch' => 'Micro Brows Retouch is a follow-up treatment for previously completed microbladed eyebrows. It is performed to refresh areas where the pigment has faded or become less visible. The procedure can help maintain the shape, color, and definition of the brows. The need for retouching varies depending on factors such as skin type and pigment retention.',

        'lipblush' => 'Lip Blush is a semi-permanent cosmetic treatment that adds a soft tint of color to the lips. It is designed to enhance the natural appearance and definition of the lips. The procedure can help create a more even-looking lip color and improve the appearance of the lip shape. The resulting color gradually fades over time and may require maintenance.',

        'liptattoo' => 'Lip Tattoo is a cosmetic tattooing procedure that adds longer-lasting color and definition to the lips. Pigment is carefully placed into the skin to create the desired lip color or appearance. It can help enhance the visual definition of the lips and create a more consistent color. The longevity and final appearance may vary depending on skin type and aftercare.',

        'microshading' => 'Microshading is a semi-permanent eyebrow technique that creates a soft, shaded makeup effect. It uses small deposits of pigment to create a more filled-in appearance. The technique is suitable for clients who prefer brows with a makeup-inspired finish. The resulting appearance can range from soft and natural to more defined, depending on the desired style.',

        'ombreshading' => 'Ombre Shading is an eyebrow shading technique that creates a gradual color transition from lighter to darker areas. The front portion of the brows is generally designed to appear softer, while the tail is more defined. This creates a smooth and polished gradient effect. It provides the appearance of professionally applied brow makeup with a semi-permanent result.',

        'eyelinertattoo' => 'Eyeliner Tattoo is a cosmetic tattoo technique that places pigment along the lash line. It is designed to create the appearance of eyeliner without requiring daily application. The treatment can make the eyes appear more defined and enhance the appearance of the lashes. The intensity and style of the eyeliner may depend on the client preference and the clinic technique.',

        'uawaxing' => 'UA Waxing is a hair-removal treatment designed specifically for the underarm area. Warm wax is applied to the skin and removed to pull unwanted hair from the root. The treatment leaves the underarm area feeling smoother and cleaner. Regular waxing may help maintain a hair-free appearance for a period of time.',

        'legwaxing' => 'Leg Waxing is a hair-removal treatment that removes unwanted hair from the legs. Wax is applied to the skin and removed along with the hair from the root. It provides a smoother appearance compared with shaving because the hair is removed from the root. The results can vary depending on individual hair growth patterns.',

        'upperlipwax' => 'Upper Lip Wax is a quick hair-removal treatment designed for unwanted hair around the upper-lip area. Wax is carefully applied to the targeted area and removed to pull out the hair from the root. The treatment provides a clean and smoother appearance. Proper aftercare can help minimize temporary skin sensitivity after treatment.',

        'hifuface' => 'HIFU Face is a focused ultrasound treatment designed to support a firmer and tighter-looking facial appearance. It delivers focused ultrasound energy to targeted layers beneath the skin. The treatment is non-surgical and is commonly used as part of facial skin-firming procedures. The number of treatments and results can vary depending on the individual skin condition and treatment goals.',

        'gelpolish' => 'Gel Polish is a nail service that applies gel-based polish to the natural nails. The polish is cured under a special lamp to create a smooth and durable finish. It generally provides a longer-lasting appearance compared with regular nail polish. Proper application and removal help maintain the condition of the natural nails.',

        'nailextension' => 'Nail Extension is a nail enhancement service that adds length and shape to the natural fingernails. Extensions are applied using specialized nail enhancement materials and are shaped according to the client preference. The service can create a longer and more polished nail appearance. Regular maintenance may be required as the natural nails grow.',

        'toenailextension' => 'Toe Nail Extension is a nail enhancement service that adds length and shape to the toenails. It is designed to improve the appearance of short, uneven, or damaged-looking toenails. Specialized nail materials are carefully applied to create the desired shape and finish. Proper maintenance is recommended to keep the extensions looking neat and attractive.',

        'toegelpolish' => 'Toe Gel Polish is a gel polish application specifically designed for the toenails. The polish is applied and cured under a special lamp to create a smooth and durable finish. It provides the toenails with a polished and well-groomed appearance. The service is suitable for clients who want longer-lasting color on their toenails.',

        'barbiearms' => 'Barbie Arms is a beauty treatment focused on improving the appearance and smoothness of the arms. The treatment may involve specialized skincare products or cosmetic procedures depending on the clinic protocol. It is intended to help the arms look smoother, cleaner, and more polished. The specific process and expected results may vary depending on the treatment used.',

        'facebotox' => 'Face Botox is a cosmetic injectable treatment used to temporarily reduce the appearance of certain facial wrinkles. It works by relaxing selected muscles responsible for repeated facial movements. The treatment is commonly used in areas where expression lines are visible. Results are temporary and should be performed by an appropriately qualified healthcare professional.',

        'wartsremoval' => 'Warts Removal is a treatment designed to remove or reduce the appearance of unwanted warts. The procedure used may depend on the size, location, and type of wart. Specialized techniques may be used to target the affected area while protecting surrounding skin. Professional assessment is recommended before treatment to determine the appropriate approach.',

        'mil iaremoval' => 'Milia Removal is a cosmetic treatment designed to remove small, keratin-filled bumps that commonly appear on the skin. The treatment focuses on carefully removing the buildup without unnecessarily damaging the surrounding skin. It can help create a smoother-looking complexion. Proper assessment and aftercare are important to reduce the risk of irritation or complications.',

        'syringomaremoval' => 'Syringoma Removal is a cosmetic treatment intended to reduce or remove small benign bumps that commonly occur around the eyes. The procedure uses a technique selected according to the location and characteristics of the bumps. The goal is to improve the appearance of the affected skin. Professional assessment is recommended because similar-looking skin growths may require different treatment approaches.',
    ];

    // Correct normalized keys and aliases for service names.
    $descriptionAliases = [
        'browlaminationwtint' => 'browlaminationwithtint',
        'microdermabrasiondiamondpeel' => 'microdermabrasiondiamondpeel',
        'melanooutmelasmameso' => 'melasmatreatment',
    ];

    // Automatically match service names with image filenames.
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

    $aliases = [
        'Brow Lamination W/Tint' => 'Brow Lamination with Tint',
        'Upper Lip Wax' => 'Upper Lip Removal',
        'Melano Out Melasma Meso' => 'Melasma Treatment',
    ];

    $aliasIndex = [];

    foreach ($aliases as $serviceName => $fileName) {
        $aliasIndex[$norm($serviceName)] = $norm($fileName);
    }

    $findServiceImage = function ($name) use ($imageIndex, $aliasIndex, $norm) {
        $key = $norm($name);

        if (isset($aliasIndex[$key], $imageIndex[$aliasIndex[$key]])) {
            return $imageIndex[$aliasIndex[$key]];
        }

        if (isset($imageIndex[$key])) {
            return $imageIndex[$key];
        }

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
                            $serviceImage = $findServiceImage($service->name);

                            $bookingUrl = route(
                                'client.appointments.create',
                                ['service' => $service->id]
                            );

                            $serviceKey = $norm($service->name);
                            $descriptionKey = $descriptionAliases[$serviceKey] ?? $serviceKey;

                            $serviceDescription = $serviceDescriptions[$descriptionKey]
                                ?? ($service->description ?: 'Description is not available yet.');
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
                        @else
                            <a
                                href="{{ route('login') }}"
                                class="primary-button service-modal-book"
                                id="modalBookButton"
                            >
                                Login to Book
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
