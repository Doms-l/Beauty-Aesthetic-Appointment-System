@extends('layouts.app')

@section('title', 'Manage Staff | M. Cares')

@section('content')

@php
    // phone field rules: numbers only, exactly 11 digits
    $phoneAttrs = 'type="tel" required maxlength="11" minlength="11" inputmode="numeric" pattern="[0-9]{11}" placeholder="09XXXXXXXXX"';
@endphp

<section class="page-hero">
    <div class="container">
        <span class="eyebrow">ADMIN · STAFF</span>
        <h1>Clinic staff</h1>
        <p>Create staff accounts, update their details, and remove staff who left the clinic.</p>
    </div>
</section>

<section class="section compact">

    <div class="container admin-two-col">

        {{-- =====================================================
             ADD STAFF
             ===================================================== --}}
        <div class="panel">

            <span class="eyebrow">ADD STAFF</span>
            <h2>Staff account</h2>

            <form method="POST"
                  action="{{ route('admin.staff.store') }}"
                  class="form-stack"
                  enctype="multipart/form-data">

                @csrf

                <div class="staff-photo-field">
                    <div class="staff-photo-preview" id="new-staff-preview"><span>Photo</span></div>
                    <div>
                        <span class="staff-photo-label">Staff photo (optional)</span>
                        <input type="file" name="profile_picture" id="new-staff-photo"
                               accept="image/jpeg,image/png,image/webp" class="staff-file-input">
                        <small>JPG, PNG or WEBP · up to 5 MB</small>
                    </div>
                </div>

                <div class="form-grid two">
                    <label>First name<input name="first_name" value="{{ old('first_name') }}" required></label>
                    <label>Last name<input name="last_name" value="{{ old('last_name') }}" required></label>
                </div>

                <label>Email<input type="email" name="email" value="{{ old('email') }}" required></label>

                <label>Phone
                    <input name="phone" value="{{ old('phone') }}" {!! $phoneAttrs !!}
                           oninput="this.value = this.value.replace(/\D/g, '').slice(0, 11);"
                           onkeydown="if (event.ctrlKey || event.metaKey) { return true; } return (event.key.length > 1) || (event.key >= '0' && event.key <= '9');"
                           onpaste="event.preventDefault(); this.value = (event.clipboardData.getData('text') || '').replace(/\D/g, '').slice(0, 11);">
                </label>

                <div class="form-grid two">
                    <label>Position<input name="position" value="{{ old('position') }}" placeholder="Beauty Specialist" required></label>
                    <label>Specialization<input name="specialization" value="{{ old('specialization') }}"></label>
                </div>

                <div class="form-grid two">
                    <label>Password
                        <div class="password-input-wrapper">
                            <input id="staff_password" type="password" name="password" minlength="8" required autocomplete="new-password">
                            <button type="button" class="password-toggle" onclick="togglePassword('staff_password', this)" aria-label="Show password">👁</button>
                        </div>
                    </label>

                    <label>Confirm password
                        <div class="password-input-wrapper">
                            <input id="staff_password_confirmation" type="password" name="password_confirmation" minlength="8" required autocomplete="new-password">
                            <button type="button" class="password-toggle" onclick="togglePassword('staff_password_confirmation', this)" aria-label="Show password">👁</button>
                        </div>
                    </label>
                </div>

                <button class="primary-button full">Create staff</button>

            </form>

        </div>


        {{-- =====================================================
             CURRENT STAFF
             ===================================================== --}}
        <div class="panel">

            <span class="eyebrow">STAFF LIST</span>
            <h2>Current staff</h2>

            <div class="admin-list">

                @forelse($staff as $member)

                    @php
                        $photo = $member->user->profile_picture
                            ? Storage::disk('public')->url($member->user->profile_picture)
                            : null;
                    @endphp

                    <div class="admin-item staff-item">

                        <div class="staff-summary">

                            @if($photo)
                                <img class="staff-avatar" src="{{ $photo }}" alt="{{ $member->user->full_name }}" loading="lazy">
                            @else
                                <span class="staff-avatar staff-avatar-empty">
                                    {{ strtoupper(mb_substr($member->user->first_name, 0, 1)) }}
                                </span>
                            @endif

                            <div>
                                <strong>{{ $member->user->full_name }}</strong>
                                <p>{{ $member->position }} · {{ $member->specialization ?: 'General' }}</p>
                                <small>{{ $member->user->email }}</small>
                                @if($member->user->phone)
                                    <small> · {{ $member->user->phone }}</small>
                                @endif
                            </div>

                        </div>

                        <span class="status {{ $member->is_available ? 'confirmed' : 'cancelled' }}">
                            {{ $member->is_available ? 'Available' : 'Unavailable' }}
                        </span>

                        <details class="staff-details">

                            <summary>Edit</summary>

                            <form method="POST"
                                  action="{{ route('admin.staff.update', $member) }}"
                                  class="form-stack mini"
                                  enctype="multipart/form-data">

                                @csrf
                                @method('PUT')

                                <div class="staff-photo-field">
                                    <div class="staff-photo-preview">
                                        @if($photo)
                                            <img src="{{ $photo }}" alt="">
                                        @else
                                            <span>No photo</span>
                                        @endif
                                    </div>
                                    <div>
                                        <span class="staff-photo-label">Change photo</span>
                                        <input type="file" name="profile_picture"
                                               accept="image/jpeg,image/png,image/webp" class="staff-file-input">
                                    </div>
                                </div>

                                <div class="form-grid two">
                                    <input name="first_name" value="{{ $member->user->first_name }}" placeholder="First name" required>
                                    <input name="last_name" value="{{ $member->user->last_name }}" placeholder="Last name" required>
                                </div>

                                <input type="email" name="email" value="{{ $member->user->email }}" placeholder="Email" required>

                                <input name="phone" value="{{ $member->user->phone }}" {!! $phoneAttrs !!}
                                       oninput="this.value = this.value.replace(/\D/g, '').slice(0, 11);"
                                       onkeydown="if (event.ctrlKey || event.metaKey) { return true; } return (event.key.length > 1) || (event.key >= '0' && event.key <= '9');"
                                       onpaste="event.preventDefault(); this.value = (event.clipboardData.getData('text') || '').replace(/\D/g, '').slice(0, 11);">

                                <div class="form-grid two">
                                    <input name="position" value="{{ $member->position }}" placeholder="Position" required>
                                    <input name="specialization" value="{{ $member->specialization }}" placeholder="Specialization">
                                </div>

                                <small class="staff-hint">New password (leave empty to keep the current one)</small>

                                <div class="form-grid two">
                                    <div class="password-input-wrapper">
                                        <input id="staff_pw_{{ $member->id }}" type="password" name="password" minlength="8" placeholder="New password" autocomplete="new-password">
                                        <button type="button" class="password-toggle" onclick="togglePassword('staff_pw_{{ $member->id }}', this)" aria-label="Show password">👁</button>
                                    </div>
                                    <div class="password-input-wrapper">
                                        <input id="staff_pwc_{{ $member->id }}" type="password" name="password_confirmation" minlength="8" placeholder="Confirm" autocomplete="new-password">
                                        <button type="button" class="password-toggle" onclick="togglePassword('staff_pwc_{{ $member->id }}', this)" aria-label="Show password">👁</button>
                                    </div>
                                </div>

                                <label class="check-row">
                                    <input type="checkbox" name="is_available" value="1" @checked($member->is_available)>
                                    Available for appointments
                                </label>

                                <button class="small-button">Save</button>

                            </form>

                            <form method="POST" action="{{ route('admin.staff.destroy', $member) }}">
                                @csrf
                                @method('DELETE')
                                <button class="danger-link"
                                        onclick="return confirm('Remove {{ addslashes($member->user->full_name) }}? Their login account will be deleted and their appointments will become Unassigned. This cannot be undone.')">
                                    Delete staff
                                </button>
                            </form>

                        </details>

                    </div>

                @empty

                    <p>No staff yet.</p>

                @endforelse

            </div>

            <div class="pagination">{{ $staff->links() }}</div>

        </div>

    </div>

</section>


<style>
    .staff-item { flex-wrap: wrap; align-items: flex-start; }

    .staff-summary { display: flex; gap: 14px; align-items: center; flex: 1 1 260px; }

    .staff-avatar {
        flex: none;
        width: 58px;
        height: 58px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid var(--line, #ddd);
    }
    .staff-avatar-empty {
        display: grid;
        place-items: center;
        font-size: 1.3rem;
        font-weight: 700;
        color: #62444D;
        background: linear-gradient(135deg, #F3BCD2, #FFAFF1);
    }

    .staff-details { flex: 1 1 100%; margin-top: 8px; }
    .staff-hint { color: var(--text-muted, #7d7074); }

    .staff-photo-field { display: flex; gap: 16px; align-items: center; }
    .staff-photo-label { display: block; font-weight: 700; color: var(--heading, #62444D); margin-bottom: 6px; }
    .staff-photo-field small { display: block; margin-top: 4px; color: var(--text-muted, #7d7074); }

    .staff-photo-preview {
        flex: none;
        width: 84px;
        height: 84px;
        border-radius: 50%;
        overflow: hidden;
        display: grid;
        place-items: center;
        font-size: .75rem;
        text-align: center;
        color: var(--text-muted, #7d7074);
        background: var(--surface-soft, #fff1f7);
        border: 2px dashed var(--line, #ccc);
    }
    .staff-photo-preview img { width: 100%; height: 100%; object-fit: cover; display: block; }

    .staff-file-input {
        width: 100%;
        padding: 8px;
        border-radius: 12px;
        border: 1px solid var(--input-border, #ded5d9);
        background: var(--input-bg, #fff);
        color: var(--text, #222);
    }
</style>


<script>
    function togglePassword(inputId, button) {

        const input = document.getElementById(inputId);

        if (input.type === 'password') {
            input.type = 'text';
            button.textContent = '🙈';
            button.setAttribute('aria-label', 'Hide password');
        } else {
            input.type = 'password';
            button.textContent = '👁';
            button.setAttribute('aria-label', 'Show password');
        }
    }

    // shows the chosen photo before saving
    (function () {
        const input = document.getElementById('new-staff-photo');
        const box = document.getElementById('new-staff-preview');

        if (!input || !box) { return; }

        input.addEventListener('change', function () {
            const file = input.files && input.files[0];

            if (!file) { box.innerHTML = '<span>Photo</span>'; return; }

            const img = document.createElement('img');
            img.src = URL.createObjectURL(file);
            img.alt = 'Selected photo';
            box.innerHTML = '';
            box.appendChild(img);
        });
    })();
</script>

@endsection
