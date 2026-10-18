<<<<<<< HEAD


=======
>>>>>>> 552cf076470698b8cd93be437339dadfac4f4270
<?php $__env->startSection('title', 'Clients | M. Cares'); ?>

<?php $__env->startSection('content'); ?>

<section class="page-hero">
    <div class="container">
        <span class="eyebrow">ADMIN · CLIENTS</span>
        <h1>Clients</h1>
        <p>
            Clients are banned automatically after
            <?php echo e(config('loyalty.cancel_ban_limit', 3)); ?> cancelled appointments.
            Only an admin can unban an account.
        </p>
    </div>
</section>

<section class="section compact">
    <div class="container">

        <div class="dashboard-actions">
            <a class="<?php echo e($onlyBanned ? 'secondary-button' : 'primary-button'); ?>"
               href="<?php echo e(route('admin.clients')); ?>">
                All clients
            </a>

            <a class="<?php echo e($onlyBanned ? 'primary-button' : 'secondary-button'); ?>"
               href="<?php echo e(route('admin.clients', ['banned' => 1])); ?>">
                Banned (<?php echo e($bannedCount); ?>)
            </a>
        </div>

        <form method="GET" action="<?php echo e(route('admin.clients')); ?>" class="form-stack" style="margin:16px 0;">
            <?php if($onlyBanned): ?>
                <input type="hidden" name="banned" value="1">
            <?php endif; ?>

            <input
                type="search"
                name="q"
                value="<?php echo e($search); ?>"
                placeholder="Search name, email or phone"
            >
        </form>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Client</th>
                        <th>Contact</th>
                        <th>Level</th>
                        <th>Cancelled</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $clients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $tier = \App\Support\ClientTier::for($client);
                    ?>

                    <tr>
                        <td><?php echo e($client->full_name); ?></td>

                        <td>
                            <?php echo e($client->email); ?><br>
                            <small><?php echo e($client->phone); ?></small>
                        </td>

                        <td><?php echo e($tier->key === 'vip' ? '👑 ' : ''); ?><?php echo e($tier->name()); ?></td>

                        <td><?php echo e($client->cancel_strikes); ?> / <?php echo e(config('loyalty.cancel_ban_limit', 3)); ?></td>

                        <td>
                            <?php if($client->isBanned()): ?>
                                <span class="status cancelled">Banned</span>
                                <br>
                                <small><?php echo e($client->banned_at->format('M d, Y h:i A')); ?></small>
                            <?php else: ?>
                                <span class="status completed">Active</span>
                            <?php endif; ?>
                        </td>

                        <td>
                            <?php if($client->isBanned()): ?>
                                <form method="POST" action="<?php echo e(route('admin.clients.unban', $client)); ?>"
                                      onsubmit="return confirm('Unban <?php echo e($client->full_name); ?>? Their cancellation count will start again from 0.');">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('PATCH'); ?>
                                    <button type="submit" class="primary-button">Unban</button>
                                </form>
                            <?php else: ?>
                                <form method="POST" action="<?php echo e(route('admin.clients.ban', $client)); ?>"
                                      onsubmit="return confirm('Ban <?php echo e($client->full_name); ?>? They will be logged out and cannot log in until you unban them.');">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('PATCH'); ?>
                                    <button type="submit" class="secondary-button">Ban</button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6">No clients found.</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="pagination"><?php echo e($clients->links()); ?></div>

    </div>
</section>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Regis D\Desktop\Beauty-Aesthetic-Appointment-System\resources\views/admin/clients.blade.php ENDPATH**/ ?>