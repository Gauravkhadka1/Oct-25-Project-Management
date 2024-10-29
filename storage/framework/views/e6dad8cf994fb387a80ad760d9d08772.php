<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<div>
    <?php $__currentLoopData = $activity->replies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reply): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="reply">
            <strong><?php echo e($reply->user->username); ?>:</strong> <?php echo e($reply->reply); ?>

        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>


</body>
</html><?php /**PATH C:\xampp\htdocs\Oct 29- Live edited-project management\resources\views/partials/activity-replies.blade.php ENDPATH**/ ?>