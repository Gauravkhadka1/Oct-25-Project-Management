

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