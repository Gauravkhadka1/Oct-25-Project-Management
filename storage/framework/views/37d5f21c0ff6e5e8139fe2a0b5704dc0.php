

<?php $__env->startSection('main-container'); ?>
<main>
    <div class="profile-page"> 
        <div class="profile-name">
            Good Morning <br><?php echo e($username); ?>

        </div>  
    </div>
    <div class="mytasks">
        <div class="current-tasks">
            <h2>Current Tasks</h2>
            <?php $__empty_1 = true; $__currentLoopData = $projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                <?php if($project->tasks->isEmpty()): ?>
                    <p>No tasks assigned to you in this project.</p>
                <?php else: ?>
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Task</th>
                                <th>Project</th>
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
                            <?php $__currentLoopData = $project->tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($index + 1); ?></td>
                                    <td><?php echo e($task->name); ?></td>
                                    <td><?php echo e($project->name); ?></td>
                                    <td><?php echo e($task->assignedBy ? $task->assignedBy->username : 'N/A'); ?></td> <!-- Show Assigned By Username -->

                                    <td><?php echo e($task->start_date); ?></td>
                                    <td><?php echo e($task->due_date); ?></td>
                                    <td><?php echo e($task->priority); ?></td>
                                    <td>
                                        <button onclick="startTimer(<?php echo e($index + 1); ?>)">Start</button>
                                        <button onclick="pauseTimer(<?php echo e($index + 1); ?>)">Pause</button>
                                        <button onclick="stopTimer(<?php echo e($index + 1); ?>)">Stop</button>
                                    </td>
                                    <td id="time-<?php echo e($index + 1); ?>">00:00:00</td>
                                    <td>
                                        <select name="status">
                                            <p>set</p>
                                        </select>
                                    </td>
                                    <td><textarea><?php echo e($task->comment); ?></textarea></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p>No projects available.</p>
            <?php endif; ?>
        </div>
        <?php if(Auth::check() && Auth::user()->email == $user->email): ?>
            <div class="edit-logout">
                <div class="edit-profile">
                    <a href="<?php echo e(route('profile.edit')); ?>">Edit Profile</a>
                </div>
                <div class="logout">
                    <a href="<?php echo e(route('logout')); ?>">Logout</a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</main>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontends.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\Oct-25-Project-Management\resources\views/frontends/dashboard.blade.php ENDPATH**/ ?>