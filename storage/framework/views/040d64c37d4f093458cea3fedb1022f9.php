<?php $__env->startSection('main-container'); ?>

<div class="expiry-page">
    <div class="expiry-filters">
        <!-- Any filters can go here -->
    </div>

    <div class="expiry-table">
        <table>
            <thead>
                <tr class="client-domain">
                    <th>
                        Domain Name
                        <a href="<?php echo e(route('expiry.index', array_merge(request()->except('sort_by', 'sort_order'), ['sort_by' => 'domain_name', 'sort_order' => request('sort_order') === 'asc' ? 'desc' : 'asc']))); ?>">
                            <img src="<?php echo e(url('public/frontend/images/sort.png')); ?>" alt="Sort">
                        </a>
                    </th>
                    <th>
                        Service Type
                        <a href="<?php echo e(route('expiry.index', array_merge(request()->except('sort_by', 'sort_order'), ['sort_by' => 'service_type', 'sort_order' => request('sort_order') === 'asc' ? 'desc' : 'asc']))); ?>">
                            <img src="<?php echo e(url('public/frontend/images/sort.png')); ?>" alt="Sort">
                        </a>
                    </th>
                    <th>
                        Days Left
                        <a href="<?php echo e(route('expiry.index', array_merge(request()->except('sort_by', 'sort_order'), ['sort_by' => 'days_left', 'sort_order' => request('sort_order') === 'asc' ? 'desc' : 'asc']))); ?>">
                            <img src="<?php echo e(url('public/frontend/images/sort.png')); ?>" alt="Sort">
                        </a>
                    </th>
                    <th>
                        Amount
                        <a href="<?php echo e(route('expiry.index', array_merge(request()->except('sort_by', 'sort_order'), ['sort_by' => 'amount', 'sort_order' => request('sort_order') === 'asc' ? 'desc' : 'asc']))); ?>">
                            <img src="<?php echo e(url('public/frontend/images/sort.png')); ?>" alt="Sort">
                        </a>
                    </th>
                    <th>
                        Expiry Date
                        <a href="<?php echo e(route('expiry.index', array_merge(request()->except('sort_by', 'sort_order'), ['sort_by' => 'expiry_date', 'sort_order' => request('sort_order') === 'asc' ? 'desc' : 'asc']))); ?>">
                            <img src="<?php echo e(url('public/frontend/images/sort.png')); ?>" alt="Sort">
                        </a>
                    </th>
                </tr>
            </thead>

            <tbody>
                <?php $__currentLoopData = $servicesData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td>
                            <a href="<?php echo e(route('client.details', ['id' => $service['client_id']])); ?>">
                                <?php echo e($service['domain_name']); ?>

                            </a>
                        </td>
                        <td><?php echo e($service['service_type']); ?></td>
                        <td><?php echo e($service['days_left']); ?> days</td>
                        <td><?php echo e($service['amount'] ?? 'N/A'); ?></td>
                        <td><?php echo e(\Carbon\Carbon::parse($service['expiry_date'])->format('d-m-Y') ?? 'N/A'); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
</div>

<style>
.expiry-page {
    padding: 20px;
}

.expiry-table img {
    width: 15px;
}

.expiry-table th {
    background-color:rgb(235, 238, 245);
    color: #2A2E34;
}

.client-domain a {
    text-decoration: none;
    color: #2A2E34;
    font-weight: 500;
}

.client-domain a:hover {
    text-decoration: underline;
}
</style>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontends.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\Oct 29- Live edited-project management\resources\views/frontends/expiry.blade.php ENDPATH**/ ?>