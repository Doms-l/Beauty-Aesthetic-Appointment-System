@extends('layouts.app')

@section('title', 'Manage Promo | M. Cares')

@section('content')

<section class="page-hero">
    <div class="container">
        <span class="eyebrow">ADMIN · PROMO</span>
        <h1>Manage promo banner</h1>
        <p>Change the poster and text shown on the home page, the dashboard slideshow, and the top announcement bar.</p>
    </div>
</section>

<section class="section compact">

    <div class="container admin-two-col">

        {{-- EDIT FORM --}}
        <div class="panel">

            <span class="eyebrow">EDIT PROMO</span>
            <h2>Current promo</h2>

            <form method="POST"
                  action="{{ route('admin.promo.update') }}"
                  class="form-stack"
                  enctype="multipart/form-data">

                @csrf
                @method('PUT')

                <label>Promo title <small>(shown under the poster)</small>
                    <input name="title" value="{{ old('title', $promo->title) }}" maxlength="150" required>
                </label>

                <label>Announcement bar text <small>(short line at the very top of every page; leave empty to reuse the title)</small>
                    <input name="bar_text" value="{{ old('bar_text', $promo->bar_text) }}" maxlength="150">
                </label>

                <div class="svc-photo-field">

                    <span class="svc-photo-label">Promo poster</span>

                    <div class="svc-photo-preview" id="promo-preview">
                        <img src="{{ $promo->image_url }}" alt="Current promo poster">
                    </div>

                    <input type="file"
                           name="image"
                           id="promo-image"
                           accept="image/jpeg,image/png,image/webp"
                           class="svc-file-input">

                    <small>JPG, PNG or WEBP · up to 5 MB · a wide poster (about 3:1) fits the slideshow best</small>

                    @if($promo->image)
                        <label class="promo-check">
                            <input type="checkbox" name="remove_image" value="1">
                            Go back to the original poster
                        </label>
                    @endif

                </div>

                <label class="promo-check">
                    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $promo->is_active))>
                    Show this promo on the website (untick to hide it everywhere)
                </label>

                <button class="primary-button full">Save promo</button>

            </form>

        </div>


        {{-- LIVE PREVIEW --}}
        <div class="panel">

            <span class="eyebrow">PREVIEW</span>
            <h2>What clients see</h2>

            @if($promo->is_active)
                <p class="promo-status on">&#9679; Live on the website and client pages</p>
            @else
                <p class="promo-status off">&#9679; Hidden. Clients do not see a promo right now</p>
            @endif

            <div class="promo-bar-preview">
                &#127872; <strong>PROMO:</strong> {{ $promo->bar_line }} &mdash; Book now &rarr;
            </div>

            @include('partials.promo-banner', ['compact' => true, 'admin' => true])

        </div>

    </div>

</section>

<style>
    .promo-check { display: flex; align-items: center; gap: 10px; font-weight: 600; }
    .promo-check input { width: auto; }
    .promo-status { font-weight: 700; margin: 0 0 14px; }
    .promo-status.on { color: #2e8b57; }
    .promo-status.off { color: #b4534f; }
    .promo-bar-preview {
        margin-bottom: 14px;
        padding: 10px 16px;
        border-radius: 12px;
        background: linear-gradient(110deg, #70475e, #b46e91, #70475e);
        color: #fff;
        text-align: center;
        font-size: 13px;
        font-weight: 600;
    }
    .promo-bar-preview strong { color: #ffe9a8; }
    .svc-photo-field { display: grid; gap: 8px; }
    .svc-photo-label { font-weight: 700; color: var(--heading, #62444D); }
    .svc-photo-field small { color: var(--text-muted, #7d7074); }
    .svc-photo-preview {
        display: grid; place-items: center; overflow: hidden;
        border-radius: 18px; border: 2px dashed var(--line, #ccc);
        background: var(--surface-soft, #fff1f7);
    }
    .svc-photo-preview img { width: 100%; max-height: 240px; object-fit: contain; display: block; }
    .svc-file-input {
        padding: 10px; border-radius: 14px; width: 100%;
        border: 1px solid var(--input-border, #ded5d9);
        background: var(--input-bg, #fff); color: var(--text, #222);
    }
</style>

<script>
    // shows the chosen poster before saving
    (function () {
        const input = document.getElementById('promo-image');
        const box = document.getElementById('promo-preview');
        if (!input || !box) { return; }

        input.addEventListener('change', function () {
            const file = input.files && input.files[0];
            if (!file) { return; }
            const img = document.createElement('img');
            img.src = URL.createObjectURL(file);
            img.alt = 'Selected poster';
            box.innerHTML = '';
            box.appendChild(img);
        });
    })();
</script>

@endsection
