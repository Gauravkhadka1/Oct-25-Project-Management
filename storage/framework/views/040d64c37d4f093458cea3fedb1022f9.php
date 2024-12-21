<?php $__env->startSection('main-container'); ?>

<div class="expiry-page">
    <div class="expiry-top">
        <div class="expiry-category">
            <p>All </p>
        </div>
        <div class="expiry-filter-search">
            <div class="filter-payments" onclick="toggleFilterList()">
                <img src="public/frontend/images/new-bar.png" alt="" class="barfilter">
                <div class="filter-count">

                    <p></p>

                </div>
                Filter
            </div>
            <div class="search-payments">
                <div class="search-icon">
                    <img src="public/frontend/images/search-light-color.png" alt="" class="searchi-icon">
                </div>
                <form action="<?php echo e(route('expiry.index')); ?>" method="GET" id="search-form">
                    <div class="search-text-area">
                        <input type="text" name="search" placeholder="search..." value="<?php echo e(request('search')); ?>" oninput="this.form.submit()">
                    </div>
                </form>
            </div>
        </div>
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
                <tr class="
                        <?php echo e($service['days_left'] <= -30 ? 'expired-critical' : ''); ?>

                        <?php echo e($service['days_left'] >= 0 && $service['days_left'] <= 7 ? 'expiring-soon' : ''); ?>

                        <?php echo e($service['days_left'] >= -29 && $service['days_left'] <= 0 ? 'expiring-immediate' : ''); ?>

                    ">

                    <td class="client-domain">
                        <a href="<?php echo e(route('client.details', ['id' => $service['client_id']])); ?>">
                            <?php echo e($service['domain_name']); ?>

                        </a>
                    </td>
                    <td><?php echo e($service['service_type']); ?></td>
                    <td><?php echo e($service['days_left']); ?> days</td>
                    <td>
                        <?php if($service['amount']): ?>
                        <?php echo e(number_format($service['amount'])); ?>

                        <?php else: ?>
                        N/A
                        <?php endif; ?>
                    </td>
                    <td><?php echo e(\Carbon\Carbon::parse($service['expiry_date'])->format('d-m-Y') ?? 'N/A'); ?></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>

        </table>
    </div>
</div>


<?php $__env->stopSection(); ?>
<?php echo $__env->make('frontends.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\Oct 29- Live edited-project management\resources\views/frontends/expiry.blade.php ENDPATH**/ ?>