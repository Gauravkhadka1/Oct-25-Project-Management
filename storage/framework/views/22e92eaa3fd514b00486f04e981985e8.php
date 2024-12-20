
        <div class="users-tasks">
            <?php
                $loggedInUser = Auth::user()->username; // Get the logged-in user's username
            ?>
        </div>


        <div class="task-board" id="user-task-board">
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
        
        // Filter tasks by status
        $tasksToDo = $tasks->filter(function ($task) {
            return $task->status === 'TO DO' || $task->status === null;
        });
        $tasksInProgress = $tasks->where('status', 'IN PROGRESS');
        $tasksQA = $tasks->where('status', 'QA');
        $tasksCompleted = $tasks->where('status', 'COMPLETED');
        $tasksClosed = $tasks->where('status', 'CLOSED');

        // Count tasks per status
        $tasksCounts = [
            'TO DO' => $tasksToDo->count(),
            'IN PROGRESS' => $tasksInProgress->count(),
            'QA' => $tasksQA->count(),
            'COMPLETED' => $tasksCompleted->count(),
            'CLOSED' => $tasksClosed->count()
        ];

        $columnNames = [
            'TO DO' => $tasksToDo,
            'IN PROGRESS' => $tasksInProgress,
            'QA' => $tasksQA,
            'COMPLETED' => $tasksCompleted,
            'CLOSED' => $tasksClosed
        ];
    ?>

    <?php $__currentLoopData = $columnNames; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status => $tasksCollection): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="task-column" id="<?php echo e(strtolower(str_replace(' ', '', $status))); ?>" data-status="<?php echo e($status); ?>" style="margin-left:15px;">
        <div class="status-n-count-dashboard">
        <div class="<?php echo e(strtolower(str_replace(' ', '', $status))); ?>-heading">
            <img src="<?php echo e(url('public/frontend/images/' . strtolower(str_replace(' ', '', $status)) . '.png')); ?>" alt="">
            <h3><?php echo e($status); ?></h3> <!-- Display the task count -->
        </div>
        <div class="task-count-dashboard">
        <?php echo e($tasksCounts[$status]); ?>

        </div>

        </div>
        <div class="task-list">
                <?php $__currentLoopData = $tasksCollection; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="task" draggable="true" data-task-id="<?php echo e($task->id); ?>" data-task-type="<?php echo e(strtolower($task->category)); ?>">
                        <div class="task-name">
                            <?php if($task->category == 'Payment'): ?>
                                <a href="<?php echo e(route('payment_task.detail', ['id' => $task->id])); ?>">
                            <?php elseif($task->category == 'Prospect'): ?>
                                <a href="<?php echo e(route('prospect_task.detail', ['id' => $task->id])); ?>">
                            <?php else: ?>
                                <a href="<?php echo e(route('task.detail', ['id' => $task->id])); ?>">
                            <?php endif; ?>
                                <p><?php echo e($task->name); ?></p>
                            </a>
                        </div>
                        <div class="in-project">In <?php echo e($task->category_name); ?></div>
                        <div class="assigne">
                            <?php if($task->assignedTo): ?>
                                <img src="<?php echo e(url('public/frontend/images/assigned-to.png')); ?>" alt="" class="assigned-to-icon">
                                : <img src="<?php echo e(asset('storage/profilepics/' . $task->assignedTo->profilepic)); ?>" 
                                alt="<?php echo e($task->assignedTo->username); ?>'s Profile Picture" class="profile-pic" id="assigned-pic"> 
                            <?php else: ?>
                                <img src="<?php echo e(url('public/frontend/images/unassigned.png')); ?>" alt="Unassigned">
                                by: N/A
                            <?php endif; ?>
                        </div>
                        <div class="due-date">
                            <img src="<?php echo e(url('public/frontend/images/due-date.png')); ?>" alt=""> 
                            : <?php echo e($task->due_date); ?>

                        </div>
                        <div class="priority">
                            <?php
                                $priorityImages = [
                                    'High' => 'priority-high.png',
                                    'Urgent' => 'priority-urgent.png',
                                    'Normal' => 'priority-normal.png',
                                ];
                                $priorityImage = isset($priorityImages[$task->priority]) ? $priorityImages[$task->priority] : 'default.png';
                            ?>
                            <img src="<?php echo e(url('public/frontend/images/' . $priorityImage)); ?>" alt="<?php echo e($task->priority); ?>">
                            : <?php echo e($task->priority); ?>

                        </div>

                        <div class="time-details">
                            <div class="start-pause">
                                <button class="btn-toggle start" id="toggle-<?php echo e($task->id); ?>" onclick="toggleTimer(<?php echo e($task->id); ?>, '<?php echo e($task->category); ?>')">
                                    <img src="<?php echo e(url('public/frontend/images/play.png')); ?>" alt="">
                                </button>
                            </div>
                            <div class="time-data" id="time-<?php echo e($task->id); ?>">00:00:00</div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
   


        <div class="schedule-table">
            <div class="schedule-table-heading">
                <h2><?php echo e($username); ?> Today Schedule</h2>
                <form method="GET" action="<?php echo e(route('user.dashboard', ['username' => $user->username])); ?>">
                    <label for="schedule-date">View Schedule:</label>
                    <input type="date" id="schedule-date" name="date" value="<?php echo e(request('date', now()->toDateString())); ?>" onchange="this.form.submit()">
                </form>
            </div>

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
                    <?php
                    $isFirstTask = true;
                    ?>
                    <?php $__currentLoopData = $tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($isFirstTask ? $interval : ' " " '); ?></td>
                        <td><?php echo e($task['task_name']); ?></td>
                        <td><?php echo e($task['project_name']); ?></td>
                        <td><?php echo e($task['time_spent']); ?></td>
                    </tr>
                    <?php
                    $isFirstTask = false;
                    ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="4">Summary - Total Time Spent per Task</th>
                    </tr>
                    <?php $__currentLoopData = $taskSummaryData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $summary): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td colspan="2"><?php echo e($summary['task_name']); ?></td>
                        <td><?php echo e($summary['project_name']); ?></td>
                        <td><?php echo e($summary['total_time_spent']); ?></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td colspan="3"><strong>Total Time Spent Today on All Tasks</strong></td>
                        <td><strong><?php echo e($totalTimeSpentAcrossTasksFormatted); ?></strong></td>
                    </tr>
                </tfoot>
            </table>
        </div>

</div>

</div>

<?php /**PATH C:\xampp\htdocs\Oct 29- Live edited-project management\resources\views/partials/task-table.blade.php ENDPATH**/ ?>