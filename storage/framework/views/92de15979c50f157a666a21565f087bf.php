<?php $__empty_1 = true; $__currentLoopData = $clients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <tr>
        <td><?php echo e($index + 1); ?></td>
        <td><?php echo e($client->company_name ?? ''); ?></td>
        <td>
            <?php if(!empty($client->website)): ?>
                <?php
                    $url = preg_match('/^(http|https):\/\//', $client->website) ? $client->website : 'http://' . $client->website;
                ?>
                <a href="<?php echo e($url); ?>" target="_blank" rel="noopener noreferrer"><?php echo e($client->website); ?></a>
            <?php else: ?>
                No Website
            <?php endif; ?>
        </td>
        <td><?php echo e($client->rating ?? ''); ?></td>
    </tr>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <tr>
        <td colspan="4" style="text-align: center;">No data available</td>
    </tr>
<?php endif; ?><?php /**PATH C:\xampp\htdocs\Oct 29- Live edited-project management\resources\views/partials/clients_table_rows.blade.php ENDPATH**/ ?>