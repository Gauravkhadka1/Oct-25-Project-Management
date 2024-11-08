
<?php
$tasks = collect(); // Create an empty collection to hold all tasks

// Add tasks from payments first
foreach ($payments as $payment) {
foreach ($payment->payment_tasks as $task) {
$task->category = 'Payment';
$task->category_name = $payment->company_name; // Store payment company name in 'category_name'
$tasks->push($task); // Add payment tasks to the collection
}
}

// Add tasks from projects second
foreach ($projects as $project) {
foreach ($project->tasks as $task) {
$task->category = 'Project';
$task->category_name = $project->name; // Store project name in 'category_name'
$tasks->push($task); // Add project tasks to the collection
}
}

// Add tasks from prospects third
foreach ($prospects as $prospect) {
foreach ($prospect->prospect_tasks as $task) {
$task->category = 'Prospect';
$task->category_name = $prospect->company_name; // Store prospect company name in 'category_name'
$tasks->push($task); // Add prospect tasks to the collection
}
}

// Flag to check if there are any tasks
$hasTasks = $tasks->isNotEmpty();
$serialNo = 1;
?>
<?php if($hasTasks): ?>
<div class="user-tasks">
<table class="task-table">
    <thead>
        <tr>
            <th>S.N</th>
            <th>Task</th>
            <th>Category Name</th> <!-- New column for Category Name -->
            <th>Category</th> <!-- New column for Category Type -->
            <th>Assigned by</th>
            <th>Start date</th>
            <th>Due date</th>
            <th>Priority</th>
            <th>Actions</th>
            <th>Timestamp</th>
            <th>Status</th>
            <th>Comment</th>
        </tr>
    </thead>
    <tbody>
        <?php $__currentLoopData = $tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr>
            <td><?php echo e($serialNo++); ?></td>
            <td><?php echo e($task->name); ?></td>
            <td><?php echo e($task->category_name); ?></td> <!-- Category Name -->
            <td>
                <span class="<?php echo e($task->category === 'Project' ? 'label-project' : ($task->category === 'Payment' ? 'label-payment' : 'label-prospect')); ?>">
                    <?php echo e($task->category); ?>

                </span>
            </td>
            <!-- Category Type (Project/Payment/Prospect) -->
            <td><?php echo e($task->assignedBy ? $task->assignedBy->username : 'N/A'); ?></td>
            <td><?php echo e($task->start_date); ?></td>
            <td><?php echo e($task->due_date); ?></td>
            <td><?php echo e($task->priority); ?></td>
            <td>
                <button class="btn-toggle start">Start</button>
            </td>
            <td id="time-<?php echo e($task->id); ?>">00:00:00</td>
            <td>
                <select name="status">
                    <option>To Do</option>
                    <option>In Progress</option>
                    <option>QA</option>
                    <option>Completed</option>
                </select>
            </td>
            <td><textarea><?php echo e($task->comment); ?></textarea></td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
</table>
<?php else: ?>
<p>No tasks available.</p>
<?php endif; ?>
</div>
<div class="schedule-table" style="margin-top: 30px;">
    <h2><?php echo e($username); ?> today schedule</h2>
    <table class="task-table">
        <thead class="schedule head">
            <tr>
                <th>Time Interval</th>
                <th>Task Name</th>
                <th>Project Name</th>
                <th>Time Spent</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $hourlySessionsData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $interval => $tasks): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if($tasks->isEmpty()): ?>
                    <tr>
                        <td><?php echo e($interval); ?></td>
                        <td colspan="3">N/A</td>
                    </tr>
                <?php else: ?>
                    <?php $__currentLoopData = $tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($interval); ?></td>
                            <td><?php echo e($task['task_name']); ?></td>
                            <td><?php echo e($task['project_name']); ?></td>
                            <td><?php echo e($task['time_spent']); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</div>

</div>

<?php /**PATH C:\xampp\htdocs\Oct 29- Live edited-project management\resources\views/partials/task-table.blade.php ENDPATH**/ ?>