

<?php $__env->startSection('title', 'Admin Profile | M. Cares'); ?>

<?php $__env->startSection('content'); ?>

<section class="page-hero">
    <div class="container">
        <span class="eyebrow">ADMIN PROFILE</span>

        <h1>Manage your admin profile.</h1>

        <p>
            Update your administrator information and profile picture.
        </p>
    </div>
</section>

<section class="section compact">

    <div class="container narrow-panel">

        <form
            method="POST"
            action="<?php echo e(route('admin.profile.update')); ?>"
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
                            alt="Admin Profile Picture"
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

                    <h3>Admin Profile Picture</h3>

                    <p>
                        Upload a picture that will appear in the admin navigation.
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
                        type="text"
                        name="first_name"
                        value="<?php echo e(old('first_name', $user->first_name)); ?>"
                        required
                    >
                </label>

                <label>
                    Last name

                    <input
                        type="text"
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
                        type="text"
                        name="phone"
                        value="<?php echo e(old('phone', $user->phone)); ?>"
                    >
                </label>

            </div>

            <label>
                Address

                <input
                    type="text"
                    name="address"
                    value="<?php echo e(old('address', $user->address)); ?>"
                >
            </label>

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

                preview.alt = 'Admin Profile Picture';

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
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Regis D\Desktop\Beauty-Aesthetic-Appointment-System\resources\views/admin/profile.blade.php ENDPATH**/ ?>