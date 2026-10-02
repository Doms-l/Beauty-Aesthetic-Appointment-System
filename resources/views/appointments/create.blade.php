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

    <label>
        Additional notes

        <textarea
            name="notes"
            rows="4"
            maxlength="1000"
            placeholder="Optional: tell the clinic anything important about your request."
        >{{ old('notes') }}</textarea>
    </label>

    <button
        class="primary-button full"
        type="submit"
    >
        Send appointment request
    </button>

</form>

    </div>

</section>

@endsection