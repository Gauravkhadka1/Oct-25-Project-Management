<?php $__env->startSection('main-container'); ?>

<div class="expiry-page">
    <div class="expiry-filters">
    <!-- Any filters can go here -->
    </div>

    <div class="expiry-table">
        <table>
            <thead>
                <tr>
                    <th>SN</th>
                    <th>
                        Domain
                        <a href="<?php echo e(route('expiry.index', ['sort' => request('sort') == 'asc' ? 'desc' : 'asc', 'column' => 'website', 'days_filter' => request('days_filter')])); ?>">
                            <img src="<?php echo e(url('public/frontend/images/sort.png')); ?>" alt="Sort">
                        </a>
                    </th>
                    <th>
                        Space
                        <a href="<?php echo e(route('expiry.index', ['sort' => request('sort') == 'asc' ? 'desc' : 'asc', 'column' => 'hosting_space', 'days_filter' => request('days_filter')])); ?>">
                            <img src="<?php echo e(url('public/frontend/images/sort.png')); ?>" alt="Sort">
                        </a>
                    </th>
                    <th>
                        Days Left
                        <a href="<?php echo e(route('expiry.index', ['sort' => request('sort') == 'asc' ? 'desc' : 'asc', 'column' => 'days_left', 'days_filter' => request('days_filter')])); ?>">
                            <img src="<?php echo e(url('public/frontend/images/sort.png')); ?>" alt="Sort">
                        </a>
                    </th>
                    <th>
                        Amount
                        <a href="<?php echo e(route('expiry.index', ['sort' => request('sort') == 'asc' ? 'desc' : 'asc', 'column' => 'hosting_amount', 'days_filter' => request('days_filter')])); ?>">
                            <img src="<?php echo e(url('public/frontend/images/sort.png')); ?>" alt="Sort">
                        </a>
                    </th>
                    <th>
                        End Date
                        <a href="<?php echo e(route('expiry.index', ['sort' => request('sort') == 'asc' ? 'desc' : 'asc', 'column' => 'hosting_expiry_date', 'days_filter' => request('days_filter')])); ?>">
                            <img src="<?php echo e(url('public/frontend/images/sort.png')); ?>" alt="Sort">
                        </a>
                    </th>
                    <th>Service Type</th>
                    <th>
                        Status
                        <a href="<?php echo e(route('expiry.index', ['sort' => request('sort') == 'asc' ? 'desc' : 'asc', 'column' => 'status', 'days_filter' => request('days_filter')])); ?>">
                            <img src="<?php echo e(url('public/frontend/images/sort.png')); ?>" alt="Sort">
                        </a>
                    </th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $clients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr style="<?php echo e($client->days_left <= 0 ? 'background-color: #ffcccc;' : ''); ?>">
                        <td><?php echo e($index + 1); ?></td>
                        <td><?php echo e($client->website); ?></td>
                        <td><?php echo e($client->hosting_space); ?></td>
                        <td><?php echo e($client->days_left); ?> days</td>
                        <td><?php echo e($client->amount); ?></td>
                        <td><?php echo e($client->expiry_date); ?></td>
                        <td><?php echo e($client->service_type); ?></td> <!-- Service type (Domain or Hosting) -->
                        <td><?php echo e($client->days_left > 0 ? 'Active' : 'Expired'); ?></td>
                        <td></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="9" style="text-align: center;">No clients found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontends.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\Oct 29- Live edited-project management\resources\views/frontends/expiry.blade.php ENDPATH**/ ?>