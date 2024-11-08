

<?php $__env->startSection('main-container'); ?>

<div class="profile-page">
        <h1><?php echo e($username); ?>'s Dashboard</h1>

        <div class="mytasks">
            <div class="current-tasks">
                <h2>Tasks for <?php echo e($username); ?></h2>
                <?php echo $__env->make('partials.task-table', ['tasks' => $tasks, 'prospectTasks' => $prospectTasks, 'paymentTasks' => $paymentTasks, 'projects' => $projects, 'prospects' => $prospects, 'payments' => $payments], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            </div>
        </div>
    </div>
    <script>
         // Load the saved time from the database when the page loads
         window.addEventListener("load", () => {
            <?php $__currentLoopData = $projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php $__currentLoopData = $project->tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                if (!timers[<?php echo e($task->id); ?>]) {
                    timers[<?php echo e($task->id); ?>] = {
                        elapsedTime: <?php echo e($task->elapsed_time * 1000); ?>,
                        running: false
                    };
                }
                console.log("Timer initialized for task ID:", <?php echo e($task->id); ?>);
                updateTimerDisplay(<?php echo e($task->id); ?>);
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        });
    document.addEventListener("DOMContentLoaded", function() {
        const userItems = document.querySelectorAll(".username-item");

        userItems.forEach(item => {
            item.addEventListener("click", function() {
                const username = this.dataset.username;
                window.location.href = `/user-dashboard/${username}`;
            });
        });
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('frontends.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\Oct 29- Live edited-project management\resources\views/frontends/user-dashboard.blade.php ENDPATH**/ ?>