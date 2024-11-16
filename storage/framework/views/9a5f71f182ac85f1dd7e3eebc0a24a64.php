

<?php $__env->startSection('main-container'); ?>
<div class="task-detail-page">
    <div class="task-details">
        <h2><?php echo e($task->name); ?></h2>
        <p><strong>Category:</strong> <?php echo e($task->category_name); ?></p>
        <p><strong>Assigned By:</strong> <?php echo e($task->assignedBy ? $task->assignedBy->username : 'N/A'); ?></p>
        <p><strong>Due Date:</strong> <?php echo e($task->due_date); ?></p>
        <p><strong>Priority:</strong> <?php echo e($task->priority); ?></p>
        <p><strong>Status:</strong> <?php echo e($task->status); ?></p>
        <!-- Add more task details as needed -->
    </div>

    <div class="task-comments">
        <h2>Comments</h2>
        <!-- Display or handle comments here -->
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontends.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\Oct 29- Live edited-project management\resources\views/frontends/taskdetail.blade.php ENDPATH**/ ?>