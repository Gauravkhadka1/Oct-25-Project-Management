<?php $__env->startSection('main-container'); ?>

<div class="expiry-page">
    <div class="expiry-filters">
        <!-- Any filters can go here -->
    </div>

    <div class="expiry-table">
        <table>
            <thead>
                <tr>
                    <th>Domain Name</th>
                    <th>Service Type</th>
                    <th>Expiry Date</th>
                    <th>Amount</th>
                    <th>Days Left</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $servicesData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($service['domain_name']); ?></td>
                        <td><?php echo e($service['service_type']); ?></td>
                        <td><?php echo e(\Carbon\Carbon::parse($service['expiry_date'])->format('d-m-Y') ?? 'N/A'); ?></td>
                        <td><?php echo e($service['amount'] ?? 'N/A'); ?></td>
                        <td>
                            <?php echo e($service['days_left']); ?> days
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontends.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\Oct 29- Live edited-project management\resources\views/frontends/expiry.blade.php ENDPATH**/ ?>