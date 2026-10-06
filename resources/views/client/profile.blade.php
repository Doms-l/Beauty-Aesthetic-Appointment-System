@extends('layouts.app')

@section('title', 'My Profile | M. Cares')

@section('content')

<section class="page-hero">

    <div class="container">

        <span class="eyebrow">
            MY PROFILE
        </span>

        <h1>
            Keep your client information updated.
        </h1>

        <p>
            This information is used when managing your appointments.
        </p>

    </div>

</section>


<section class="section compact">

    <div class="container narrow-panel">

        <form
            method="POST"
            action="{{ route('client.profile.update') }}"
            class="form-stack profile-form"
            enctype="multipart/form-data"
        >

            @csrf

            @method('PUT')


            {{-- PROFILE PICTURE --}}

            <div class="profile-picture-section">

                <div class="profile-picture-preview">

                    @if($user->profile_picture)

                        <img
                            src="{{ asset('storage/' . $user->profile_picture) }}"
                            alt="Profile Picture"
                            id="profile-preview"
                        >

                    @else

                        <div
                            class="profile-picture-placeholder"
                            id="profile-placeholder"
                        >
                            {{ strtoupper(substr($user->first_name, 0, 1)) }}
                        </div>

                    @endif

                </div>


                <div class="profile-picture-info">

                    <h3>
                        Profile Picture
                    </h3>

                    <p>
                        Upload a profile picture that will appear
                        in the navigation bar.
                    </p>

                    <label class="profile-upload-button">

                        Choose Picture

                        <input
                            type="file"
                            name="profile_picture"
                            accept="image/png,image/jpeg,image/webp"
                            id="profile-picture-input"
                            hidden
                        >

                    </label>

                    <small>
                        JPG, PNG, or WEBP. Maximum 2MB.
                    </small>

                </div>

            </div>


            {{-- PERSONAL INFORMATION --}}

            <div class="form-grid two">

                <label>

                    First name

                    <input
                        name="first_name"
                        value="{{ old('first_name', $user->first_name) }}"
                        required
                    >

                </label>


                <label>

                    Last name

                    <input
                        name="last_name"
                        value="{{ old('last_name', $user->last_name) }}"
                        required
                    >

                </label>

            </div>


            <div class="form-grid two">

                <label>

                    Email

                    <input
                        type="email"
                        name="email"
                        value="{{ old('email', $user->email) }}"
                        required
                    >

                </label>


                <label>

                    Phone

                    <input
    type="tel"
    name="phone"
    value="{{ old('phone', $user->phone) }}"
    required
    maxlength="11"
    minlength="11"
    inputmode="numeric"
    pattern="[0-9]{11}"
    placeholder="09XXXXXXXXX"
    oninput="this.value = this.value.replace(/\D/g, '').slice(0, 11);"
    onkeydown="return event.key >= '0' && event.key <= '9' || ['Backspace', 'Delete', 'ArrowLeft', 'ArrowRight', 'Tab'].includes(event.key);"
>
                </label>

            </div>


            <div class="form-grid two">

                <label>

                    Date of birth

                    <input
                        type="date"
                        name="date_of_birth"
                        value="{{ old(
                            'date_of_birth',
                            optional($user->date_of_birth)->format('Y-m-d')
                        ) }}"
                    >

                </label>


                <label>

                    Address

                    <input
                        name="address"
                        value="{{ old('address', $user->address) }}"
                    >

                </label>

            </div>


            <button
                class="primary-button"
                type="submit"
            >
                Save Changes
            </button>

        </form>

    </div>

</section>


<script>

document.addEventListener('DOMContentLoaded', function () {

    const input = document.getElementById('profile-picture-input');

    if (!input) {
        return;
    }

    input.addEventListener('change', function (event) {

        const file = event.target.files[0];

        if (!file) {
            return;
        }

        const reader = new FileReader();

        reader.onload = function (e) {

            let preview =
                document.getElementById('profile-preview');

            const placeholder =
                document.getElementById('profile-placeholder');


            if (placeholder) {
                placeholder.remove();
            }


            if (!preview) {

                preview = document.createElement('img');

                preview.id = 'profile-preview';

                preview.alt = 'Profile Picture';

                document
                    .querySelector('.profile-picture-preview')
                    .appendChild(preview);

            }


            preview.src = e.target.result;

        };

        reader.readAsDataURL(file);

    });

});

</script>

@endsection