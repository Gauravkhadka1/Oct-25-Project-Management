<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>You were mentioned!</title>
</head>
<body>
<p>Hello <?php echo e($mentionedUsername); ?>,</p>
<p><?php echo e($mentioningUsername); ?> mentioned you in a comment in the company: <?php echo e($companyName); ?>.</p>
<p>Comment: <?php echo e($activityDetails); ?></p>

<!-- <p><a href="<?php echo e($activityLink); ?>">View Activity</a></p> -->

</body>
</html>
<?php /**PATH C:\xampp\htdocs\Oct 29- Live edited-project management\resources\views/emails/mentioned_user_notification.blade.php ENDPATH**/ ?>