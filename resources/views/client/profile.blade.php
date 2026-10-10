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


{{-- =====================================================
     DANGER ZONE: DELETE ACCOUNT
     ===================================================== --}}
<section class="section compact">

    <div class="container narrow-panel">

        <div class="danger-zone">

            <h3>Delete account</h3>

            <p>
                This permanently removes your account and personal information,
                and you will no longer be able to log in.
                Your upcoming appointments will be cancelled.
                This cannot be undone.
            </p>

            <details class="danger-details" @if($errors->has('password') || $errors->has('confirmation')) open @endif>

                <summary>I want to delete my account</summary>

                <form method="POST"
                      action="{{ route('client.profile.destroy') }}"
                      class="form-stack"
                      onsubmit="return confirm('Delete your account for good? This cannot be undone.')">

                    @csrf
                    @method('DELETE')

                    <label>
                        Your password
                        <input type="password" name="password" required autocomplete="current-password">
                    </label>

                    <label>
                        Type <strong>DELETE</strong> to confirm
                        <input name="confirmation" required autocomplete="off" placeholder="DELETE">
                    </label>

                    @error('password')
                        <p class="danger-error">{{ $message }}</p>
                    @enderror

                    @error('confirmation')
                        <p class="danger-error">{{ $message }}</p>
                    @enderror

                    <button type="submit" class="danger-button">Delete my account</button>

                </form>

            </details>

        </div>

    </div>

</section>

<style>
    .danger-zone {
        padding: 24px 26px;
        border-radius: 22px;
        border: 1px solid rgba(214, 75, 107, .45);
        background: rgba(214, 75, 107, .07);
    }
    .danger-zone h3 { margin: 0 0 8px; color: #d64b6b; }
    .danger-zone p { margin: 0 0 14px; color: var(--text, #222); }

    .danger-details summary {
        cursor: pointer;
        font-weight: 700;
        color: #d64b6b;
    }
    .danger-details form { margin-top: 16px; }

    .danger-error { margin: 0; color: #d64b6b; font-weight: 600; }

    .danger-button {
        border: 0;
        cursor: pointer;
        font: inherit;
        font-weight: 700;
        padding: 14px 24px;
        border-radius: 999px;
        color: #fff;
        background: #d64b6b;
    }
    .danger-button:hover { background: #bf3a59; }
</style>


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