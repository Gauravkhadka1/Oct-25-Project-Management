<?php $__currentLoopData = $clients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php
        $endDate = \Carbon\Carbon::parse($client->hosting_expiry_date);
        $daysLeft = (int) now()->diffInDays($endDate, false);
    ?>
    <tr style="<?php echo e($daysLeft <= 0 ? 'background-color: #FD4751; color: #f5f5f5;' : ''); ?>">
        <td><?php echo e($index + 1); ?></td>
        <td><?php echo e($client->website); ?></td>
        <td><?php echo e($client->hosting_space); ?></td>
        <td><?php echo e($daysLeft . ' days'); ?></td>
        <td><?php echo e($client->hosting_amount); ?></td>
        <td><?php echo e($client->hosting_expiry_date); ?></td>
        <td><?php echo e($client->service_type ?? 'N/A'); ?></td>
        <td><?php echo e($daysLeft > 0 ? 'Active' : 'Expired'); ?></td>
        <td>Action</td>
    </tr>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php /**PATH C:\xampp\htdocs\Oct 29- Live edited-project management\resources\views/partials/clients-table.blade.php ENDPATH**/ ?>