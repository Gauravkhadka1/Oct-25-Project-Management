<!DOCTYPE html>
<html>
<head>
    <title>You Were Mentioned in a Project Task</title>
</head>
<body>
    <h1>You Were Mentioned!</h1>
    <p>Hi <?php echo e($mentionedUser->username); ?>,</p>

    <p><?php echo e($mentioningUsername); ?> mentioned you in the task <strong><?php echo e($taskName); ?></strong> under the project <strong><?php echo e($projectName); ?></strong>.</p>

    <p><strong>Comment:</strong> <?php echo e($comment); ?></p>

    

    <p>Best regards,<br>Your Team</p>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\Oct 29- Live edited-project management\resources\views/emails/mentioned_user_notification_project.blade.php ENDPATH**/ ?>