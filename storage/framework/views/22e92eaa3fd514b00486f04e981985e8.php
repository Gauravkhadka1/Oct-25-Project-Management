
        <div class="users-tasks">
            <?php
                $loggedInUser = Auth::user()->username; // Get the logged-in user's username
            ?>
        </div>


        <div class="task-board">
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
            
            $tasksToDo = $tasks->filter(function ($task) {
                return $task->status === 'To Do' || $task->status === null;
            });
                $tasksInProgress = $tasks->where('status', 'In Progress');
                $tasksQA = $tasks->where('status', 'QA');
                $tasksCompleted = $tasks->where('status', 'Completed');
                $tasksClosed = $tasks->where('status', 'Closed');
            ?>

            <!-- Column for To Do tasks -->
            <div class="task-column" id="todo" data-status="To Do">
                <div class="todo-heading">
                    <img src="<?php echo e(url ('frontend/images/todo.png')); ?>" alt="">
                    <h3>To Do</h3>
                </div>
                
                <div class="task-list">
                    <?php $__currentLoopData = $tasksToDo; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="task" draggable="true" data-task-id="<?php echo e($task->id); ?>" data-task-type="<?php echo e(strtolower($task->category)); ?>">
                            <div class="task-name">
                            <p><?php echo e($task->name); ?></p>
                            </div>
                            <div class="in-project">
                            in <?php echo e($task->category_name); ?>

                            </div>
                            <div class="assigne">
                                Assigned by: <?php echo e($task->assignedBy ? $task->assignedBy->username : 'N/A'); ?>

                            </div>
                            <div class="due-date">
                            Due Date:<?php echo e($task->due_date); ?>

                            </div>
                            <div class="priority">
                            Priority: <?php echo e($task->priority); ?>

                            </div>
                            <div class="time-details">
                                <div class="start-pause">
                                <button class="btn-toggle start" id="toggle-<?php echo e($task->id); ?>" onclick="toggleTimer(<?php echo e($task->id); ?>, '<?php echo e($task->category); ?>')"><img src="<?php echo e(url ('frontend/images/play.png')); ?>" alt=""></button>
                                </div>
                                <div class="time-data"id="time-<?php echo e($task->id); ?>">00:00:00
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>


            <!-- Column for In Progress tasks -->
            <div class="task-column" id="inprogress" data-status="In Progress">
                <div class="inprogress-heading">
                    <img src="<?php echo e(url ('frontend/images/inprogress.png')); ?>" alt="">
                    <h3>In Progress</h3>
                </div>
             
                <div class="task-list">
                    <?php $__currentLoopData = $tasksInProgress; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="task" draggable="true" data-task-id="<?php echo e($task->id); ?>" data-task-type="<?php echo e(strtolower($task->category)); ?>">
                        <div class="task-name">
                            <p><?php echo e($task->name); ?></p>
                            </div>
                            <div class="in-project">
                            in <?php echo e($task->category_name); ?>

                            </div>
                            <div class="assigne">
                                Assigned by: <?php echo e($task->assignedBy ? $task->assignedBy->username : 'N/A'); ?>

                            </div>
                            <div class="due-date">
                            Due Date:<?php echo e($task->due_date); ?>

                            </div>
                            <div class="priority">
                            Priority: <?php echo e($task->priority); ?>

                            </div>
                            <div class="time-details">
                                <div class="start-pause">
                                <button class="btn-toggle start" id="toggle-<?php echo e($task->id); ?>" onclick="toggleTimer(<?php echo e($task->id); ?>, '<?php echo e($task->category); ?>')"><img src="<?php echo e(url ('frontend/images/play.png')); ?>" alt=""></button>
                                </div>
                                <div class="time-data"id="time-<?php echo e($task->id); ?>">00:00:00
                                </div>
                            </div>
                            <!-- Additional task details here -->
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>

            <!-- Column for QA tasks -->
            <div class="task-column" id="qa" data-status="QA">
                <div class="qa-heading">
                    <img src="<?php echo e(url ('frontend/images/qa.png')); ?>" alt="">
                    <h3>QA</h3>
                </div>
                <div class="task-list">
                    <?php $__currentLoopData = $tasksQA; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="task" draggable="true" data-task-id="<?php echo e($task->id); ?>" data-task-type="<?php echo e(strtolower($task->category)); ?>">
                        <div class="task-name">
                            <p><?php echo e($task->name); ?></p>
                            </div>
                            <div class="in-project">
                            in <?php echo e($task->category_name); ?>

                            </div>
                            <div class="assigne">
                                Assigned by: <?php echo e($task->assignedBy ? $task->assignedBy->username : 'N/A'); ?>

                            </div>
                            <div class="due-date">
                            Due Date:<?php echo e($task->due_date); ?>

                            </div>
                            <div class="priority">
                            Priority: <?php echo e($task->priority); ?>

                            </div>
                            <div class="time-details">
                                <div class="start-pause">
                                <button class="btn-toggle start" id="toggle-<?php echo e($task->id); ?>" onclick="toggleTimer(<?php echo e($task->id); ?>, '<?php echo e($task->category); ?>')"><img src="<?php echo e(url ('frontend/images/play.png')); ?>" alt=""></button>
                                </div>
                                <div class="time-data"id="time-<?php echo e($task->id); ?>">00:00:00
                                </div>
                            </div>
                            <!-- Additional task details here -->
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>

            <!-- Column for Completed tasks -->
            <div class="task-column" id="completed" data-status="Completed">
                <div class="completed-heading">
                    <img src="<?php echo e(url ('frontend/images/completed.png')); ?>" alt="">
                    <h3>Completed</h3>
                </div>
                <div class="task-list">
                    <?php $__currentLoopData = $tasksCompleted; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="task" draggable="true" data-task-id="<?php echo e($task->id); ?>" data-task-type="<?php echo e(strtolower($task->category)); ?>">
                        <div class="task-name">
                            <p><?php echo e($task->name); ?></p>
                            </div>
                            <div class="in-project">
                            in <?php echo e($task->category_name); ?>

                            </div>
                            <div class="assigne">
                                Assigned by: <?php echo e($task->assignedBy ? $task->assignedBy->username : 'N/A'); ?>

                            </div>
                            <div class="due-date">
                            Due Date:<?php echo e($task->due_date); ?>

                            </div>
                            <div class="priority">
                            Priority: <?php echo e($task->priority); ?>

                            </div>
                            <div class="time-details">
                                <div class="start-pause">
                                <button class="btn-toggle start" id="toggle-<?php echo e($task->id); ?>" onclick="toggleTimer(<?php echo e($task->id); ?>, '<?php echo e($task->category); ?>')"><img src="<?php echo e(url ('frontend/images/play.png')); ?>" alt=""></button>
                                </div>
                                <div class="time-data"id="time-<?php echo e($task->id); ?>">00:00:00
                                </div>
                            </div>
                            <!-- Additional task details here -->
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>

            <!-- Column for Closed tasks -->
            <div class="task-column" id="closed" data-status="Closed">
                <div class="closed-heading">
                    <img src="<?php echo e(url ('frontend/images/closed.png')); ?>" alt="">
                    <h3>Closed</h3>
                </div>
                <div class="task-list">
                    <?php $__currentLoopData = $tasksClosed; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="task" draggable="true" data-task-id="<?php echo e($task->id); ?>" data-task-type="<?php echo e(strtolower($task->category)); ?>">
                        <div class="task-name">
                            <p><?php echo e($task->name); ?></p>
                            </div>
                            <div class="in-project">
                            in <?php echo e($task->category_name); ?>

                            </div>
                            <div class="assigne">
                                Assigned by: <?php echo e($task->assignedBy ? $task->assignedBy->username : 'N/A'); ?>

                            </div>
                            <div class="due-date">
                            Due Date:<?php echo e($task->due_date); ?>

                            </div>
                            <div class="priority">
                            Priority: <?php echo e($task->priority); ?>

                            </div>
                            <div class="time-details">
                                <div class="start-pause">
                                <button class="btn-toggle start" id="toggle-<?php echo e($task->id); ?>" onclick="toggleTimer(<?php echo e($task->id); ?>, '<?php echo e($task->category); ?>')"><img src="<?php echo e(url ('frontend/images/play.png')); ?>" alt=""></button>
                                </div>
                                <div class="time-data"id="time-<?php echo e($task->id); ?>">00:00:00
                                </div>
                            </div>
                            <!-- Additional task details here -->
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
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