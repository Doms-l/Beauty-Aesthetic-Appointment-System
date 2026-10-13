{{-- The automatic logo for a social account: @include('partials.social-icon', ['link' => $link]) --}}
@if($link->icon_path)
    <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false"><path d="{{ $link->icon_path }}"/></svg>
@else
    {{ strtoupper(mb_substr($link->label, 0, 1)) }}
@endif
