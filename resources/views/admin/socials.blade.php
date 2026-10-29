@extends('layouts.app')

@section('title', 'Social Accounts | M. Care')

@section('content')

<section class="page-hero">
    <div class="container">
        <span class="eyebrow">ADMIN · SOCIAL ACCOUNTS</span>
        <h1>Manage social accounts</h1>
        <p>These show in the website footer. Paste a link, email or phone number and the logo is picked for you automatically.</p>
    </div>
</section>

<section class="section compact">

    <div class="container admin-two-col">

        {{-- =====================================================
             ADD A NEW SOCIAL ACCOUNT
             ===================================================== --}}
        <div class="panel">

            <span class="eyebrow">ADD ACCOUNT</span>
            <h2>New social account</h2>

            <form method="POST"
                  action="{{ route('admin.socials.store') }}"
                  class="form-stack"
                  data-social-form>

                @csrf

                <label>Link, email or phone number
                    <input name="url"
                           value="{{ old('url') }}"
                           maxlength="255"
                           placeholder="https://www.instagram.com/yourpage"
                           data-social-url
                           required>
                </label>

                <label>Name shown in the footer
                    <input name="label"
                           value="{{ old('label') }}"
                           maxlength="100"
                           placeholder="e.g. @macayla.cares"
                           data-social-label
                           required>
                </label>

                <label>Platform / logo
                    <select name="platform" data-social-platform>
                        <option value="auto" @selected(old('platform', 'auto') === 'auto')>Auto-detect from the link (recommended)</option>
                        @foreach($platforms as $key => $platform)
                            <option value="{{ $key }}" @selected(old('platform') === $key)>{{ $platform['name'] }}</option>
                        @endforeach
                    </select>
                </label>

                {{-- live preview of the generated logo --}}
                <div class="social-preview">
                    <span class="social-preview-title">Preview</span>
                    <div class="social-preview-chip">
                        <span class="footer-icon" data-social-icon></span>
                        <span data-social-text>Your account name</span>
                    </div>
                    <small data-social-detected>Type a link to see its logo.</small>
                </div>

                <label>Order <small>(1 = first in the footer; leave empty to add at the end)</small>
                    <input type="number" name="sort_order" min="0" max="9999" value="{{ old('sort_order') }}">
                </label>

                <label class="promo-check">
                    <input type="checkbox" name="is_active" value="1" @checked(old('url') === null || old('is_active'))>
                    Show in the footer
                </label>

                <button class="primary-button full">Add social account</button>

            </form>

        </div>


        {{-- =====================================================
             CURRENT SOCIAL ACCOUNTS
             ===================================================== --}}
        <div class="panel">

            <span class="eyebrow">ACCOUNT LIST</span>
            <h2>Current social accounts</h2>

            <div class="admin-list">

                @forelse($links as $link)

                    <div class="admin-item svc-item">

                        <div class="svc-summary">

                            <span class="footer-icon social-list-icon">
                                @include('partials.social-icon', ['link' => $link])
                            </span>

                            <div>
                                <strong>{{ $link->label }}</strong>
                                <p>{{ $link->platform_name }} · {{ $link->is_active ? 'Shown in the footer' : 'Hidden' }}</p>
                                <small>{{ $link->url }}</small>
                            </div>

                        </div>

                        <details class="svc-details">

                            <summary>Edit</summary>

                            <form method="POST"
                                  action="{{ route('admin.socials.update', $link) }}"
                                  class="form-stack mini"
                                  data-social-form>

                                @csrf
                                @method('PUT')

                                <label>Link, email or phone number
                                    <input name="url" value="{{ $link->url }}" maxlength="255" data-social-url required>
                                </label>

                                <label>Name shown in the footer
                                    <input name="label" value="{{ $link->label }}" maxlength="100" data-social-label required>
                                </label>

                                <label>Platform / logo
                                    <select name="platform" data-social-platform>
                                        @php
                                            // keep "Auto-detect" selected while the saved logo is the one auto-detect would pick
                                            $isAuto = \App\Support\SocialPlatforms::detect($link->url) === $link->platform;
                                        @endphp
                                        <option value="auto" @selected($isAuto)>Auto-detect from the link</option>
                                        @foreach($platforms as $key => $platform)
                                            <option value="{{ $key }}" @selected(!$isAuto && $link->platform === $key)>{{ $platform['name'] }}</option>
                                        @endforeach
                                    </select>
                                </label>

                                <div class="social-preview">
                                    <span class="social-preview-title">Preview</span>
                                    <div class="social-preview-chip">
                                        <span class="footer-icon" data-social-icon></span>
                                        <span data-social-text></span>
                                    </div>
                                    <small data-social-detected></small>
                                </div>

                                <label>Order
                                    <input type="number" name="sort_order" min="0" max="9999" value="{{ $link->sort_order }}">
                                </label>

                                <label class="promo-check">
                                    <input type="checkbox" name="is_active" value="1" @checked($link->is_active)>
                                    Show in the footer
                                </label>

                                <button class="small-button">Save</button>

                            </form>

                            <form method="POST" action="{{ route('admin.socials.destroy', $link) }}">
                                @csrf
                                @method('DELETE')
                                <button class="danger-link"
                                        onclick="return confirm('Delete this social account? This cannot be undone.')">
                                    Delete
                                </button>
                            </form>

                        </details>

                    </div>

                @empty

                    <p>No social accounts yet. Add your first one on the left.</p>

                @endforelse

            </div>

        </div>

    </div>

</section>


<style>
    .promo-check { display: flex; align-items: center; gap: 10px; font-weight: 600; }
    .promo-check input { width: auto; }

    .svc-item { align-items: flex-start; }
    .svc-summary { display: flex; gap: 14px; align-items: center; }
    .svc-details { flex: 1 1 340px; }
    .svc-summary small { word-break: break-all; }

    /* the pink round logo, same as in the footer */
    .social-list-icon, .social-preview .footer-icon {
        flex: none;
        width: 44px;
        height: 44px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: #e91e8c;
        color: #ffffff;
        font-weight: bold;
        font-size: 20px;
    }
    .social-list-icon svg, .social-preview .footer-icon svg { width: 22px; height: 22px; }

    .social-preview {
        display: grid;
        gap: 8px;
        padding: 14px;
        border-radius: 16px;
        background: #111111;
        color: #ffffff;
    }
    .social-preview-title { font-size: .72rem; letter-spacing: .14em; text-transform: uppercase; color: #f3d6e5; }
    .social-preview-chip { display: flex; align-items: center; gap: 12px; font-size: 15px; word-break: break-word; }
    .social-preview small { color: #e8c9d9; }
</style>

<script>
(function () {

    // every platform: key => { name, type, domains, path }  (from the server)
    const PLATFORMS = @json($platforms);

    function hostOf(value) {
        let v = value.trim();
        if (!/^[a-z][a-z0-9+.\-]*:\/\//i.test(v)) { v = 'https://' + v.replace(/^\/+/, ''); }
        try { return new URL(v).hostname.toLowerCase().replace(/^www\./, ''); } catch (e) { return ''; }
    }

    // same rules as the server, so the preview matches what gets saved
    function detect(value) {
        const v = value.trim();
        if (v === '') { return 'website'; }
        if (v.indexOf('/') === -1 && /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v)) { return 'email'; }
        if (/^\+?[0-9][0-9\s\-().]{5,24}$/.test(v)) { return 'phone'; }

        const host = hostOf(v);
        if (host) {
            for (const key in PLATFORMS) {
                for (const d of PLATFORMS[key].domains) {
                    if (host === d || host.endsWith('.' + d)) { return key; }
                }
            }
        }
        return 'website';
    }

    document.querySelectorAll('[data-social-form]').forEach(function (form) {

        const url      = form.querySelector('[data-social-url]');
        const label    = form.querySelector('[data-social-label]');
        const select   = form.querySelector('[data-social-platform]');
        const icon     = form.querySelector('[data-social-icon]');
        const text     = form.querySelector('[data-social-text]');
        const detected = form.querySelector('[data-social-detected]');

        function refresh() {

            const auto = select.value === 'auto';
            const key  = auto ? detect(url.value) : select.value;
            const p    = PLATFORMS[key];

            icon.innerHTML = p
                ? '<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="' + p.path + '"></path></svg>'
                : '';

            text.textContent = label.value.trim() || url.value.trim() || 'Your account name';

            detected.textContent = !p ? '' :
                (auto
                    ? (url.value.trim() ? 'Logo detected: ' + p.name : 'Type a link to see its logo.')
                    : 'Logo chosen: ' + p.name);

            // helpful placeholder for the chosen type
            if (p && p.type === 'phone') { url.placeholder = '09155168312'; }
            else if (p && p.type === 'email') { url.placeholder = 'hello@yourclinic.com'; }
        }

        [url, label, select].forEach(function (el) {
            el.addEventListener('input', refresh);
            el.addEventListener('change', refresh);
        });

        refresh();
    });

})();
</script>

@endsection
