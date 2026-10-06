

<?php $__env->startSection('title', 'My Profile | M. Cares'); ?>

<?php $__env->startSection('content'); ?>

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
            action="<?php echo e(route('client.profile.update')); ?>"
            class="form-stack profile-form"
            enctype="multipart/form-data"
        >

            <?php echo csrf_field(); ?>

            <?php echo method_field('PUT'); ?>


            

            <div class="profile-picture-section">

                <div class="profile-picture-preview">

                    <?php if($user->profile_picture): ?>

                        <img
                            src="<?php echo e(asset('storage/' . $user->profile_picture)); ?>"
                            alt="Profile Picture"
                            id="profile-preview"
                        >

                    <?php else: ?>

                        <div
                            class="profile-picture-placeholder"
                            id="profile-placeholder"
                        >
                            <?php echo e(strtoupper(substr($user->first_name, 0, 1))); ?>

                        </div>

                    <?php endif; ?>

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


            

            <div class="form-grid two">

                <label>

                    First name

                    <input
                        name="first_name"
                        value="<?php echo e(old('first_name', $user->first_name)); ?>"
                        required
                    >

                </label>


                <label>

                    Last name

                    <input
                        name="last_name"
                        value="<?php echo e(old('last_name', $user->last_name)); ?>"
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
                        value="<?php echo e(old('email', $user->email)); ?>"
                        required
                    >

                </label>


                <label>

                    Phone

                    <input
                        name="phone"
                        value="<?php echo e(old('phone', $user->phone)); ?>"
                        required
                    >

                </label>

            </div>


            <div class="form-grid two">

                <label>

                    Date of birth

                    <input
                        type="date"
                        name="date_of_birth"
                        value="<?php echo e(old(
                            'date_of_birth',
                            optional($user->date_of_birth)->format('Y-m-d')
                        )); ?>"
                    >

                </label>


                <label>

                    Address

                    <input
                        name="address"
                        value="<?php echo e(old('address', $user->address)); ?>"
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

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Regis D\Desktop\Beauty-Aesthetic-Appointment-System\resources\views/client/profile.blade.php ENDPATH**/ ?>