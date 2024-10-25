<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Tasks</title>
</head>
<body>
    <h1>Tasks for Project ID: <?php echo e($projectId); ?></h1>
    <?php if($tasks->isEmpty()): ?>
        <p>No tasks found for this project.</p>
    <?php else: ?>
        <ul>
            <?php $__currentLoopData = $tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><?php echo e($task->name); ?> - <?php echo e($task->priority); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    <?php endif; ?>
</body>
</html><?php /**PATH C:\xampp\htdocs\new project management\resources\views/frontends/tasks.blade.php ENDPATH**/ ?>