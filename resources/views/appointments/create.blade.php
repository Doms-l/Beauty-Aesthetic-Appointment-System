@extends('layouts.app')

@section('title', 'Book Appointment | M. Cares')

@section('content')

<section class="page-hero">
    <div class="container">
        <span class="eyebrow">BOOKING</span>

        <h1>Request an appointment.</h1>

        <p>
            Select your preferred service, date, and time.
            The clinic will confirm your request.
        </p>
    </div>
</section>

<section class="section compact">

    <div class="container narrow-panel">

        @include('partials.client-tier')

        @include('partials.perks-banner')

        <form
    method="POST"
    action="{{ route('client.appointments.store') }}"
    class="form-stack"
>

    @csrf

    <div
        id="appointment-picker"
        data-services='@json($services)'
        data-selected-service="{{ request('service') }}"
    ></div>

    @include('partials.provider-picker')

    <label>
        Additional notes

        <textarea
            id="booking-notes"
            name="notes"
            rows="4"
            maxlength="1000"
            placeholder="Optional: tell the clinic anything important about your request."
        >{{ old('notes') }}</textarea>
    </label>

    <p class="form-help" style="margin-top:-8px;">
        Reminder: you can type up to <strong>1000 characters</strong>
        (letters, numbers, spaces and symbols). Keep it short, for example
        allergies, skin concerns or special requests. Do not write passwords
        or payment details here.
        <br>
        <span id="booking-notes-count" style="font-weight:700;">0 / 1000</span> characters used
    </p>

    <button
        class="primary-button full"
        type="submit"
    >
        Send appointment request
    </button>

</form>

    </div>

</section>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var box = document.getElementById('booking-notes');
    var count = document.getElementById('booking-notes-count');

    if (!box || !count) return;

    function update() {
        var used = box.value.length;
        var max = box.maxLength > 0 ? box.maxLength : 1000;

        count.textContent = used + ' / ' + max;
        count.style.color = used >= max ? '#e5484d' : (used >= max * 0.9 ? '#e9a23b' : '');
    }

    box.addEventListener('input', update);
    update();
});
</script>

@endsection
