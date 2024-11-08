<?php $__env->startSection('main-container'); ?>

<main>
    <?php
  $allowedEmails = ['gaurav@webtech.com.np', 'suraj@webtechnepal.com', 'sudeep@webtechnepal.com', 'sabita@webtechnepal.com'];
  $user = auth()->user();
  $username = $user->username;
  ?>
    <div class="profile-page">
    <div class="users-tasks">
            <?php
                $loggedInUser = Auth::user()->username; // Get the logged-in user's username
            ?>
        </div>


        <div class="mytasks">
    <div class="current-tasks">
        <h2><?php echo e($loggedInUser); ?> Tasks</h2> 
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
                                    <button class="btn-toggle start" id="toggle-<?php echo e($task->id); ?>" onclick="toggleTimer(<?php echo e($task->id); ?>)">Start</button>
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
   
</div>
<div class="schedule-table">
    <div class="schedule-table-heading">
        <h2><?php echo e($loggedInUser); ?>'s Today's Schedule</h2>
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


    <script>
        

        let timers = {};

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

        function toggleTimer(taskId) {
            const timer = timers[taskId];
            const button = document.getElementById(`toggle-${taskId}`);
            
            if (timer) {
                if (timer.running) {
                    // If timer is running, pause it
                    pauseTimer(taskId);
                    button.innerText = "Resume";
                    button.classList.remove("pause");
                    button.classList.add("start");
                } else {
                    // If timer is paused or not started, start/resume it
                    startTimer(taskId);
                    button.innerText = "Pause";
                    button.classList.remove("start");
                    button.classList.add("pause");
                }
            } else {
                console.error(`Timer not found for task ID: ${taskId}`);
            }
        }

        function startTimer(taskId) {
            const timer = timers[taskId];
            if (timer) {
                timer.startTime = new Date().getTime() - timer.elapsedTime;
                timer.running = true;
                
                console.log(`Starting/resuming timer for task ID: ${taskId}`);
                sendTimerUpdate(taskId, timer.elapsedTime / 1000, `/tasks/${taskId}/start-timer`);
                
                updateTimer(taskId);
            }
        }

        function pauseTimer(taskId) {
            const timer = timers[taskId];
            if (timer && timer.running) {
                timer.elapsedTime = new Date().getTime() - timer.startTime;
                timer.running = false;
                
                console.log(`Pausing timer for task ${taskId}, elapsed time: ${timer.elapsedTime}`);
                
                sendTimerUpdate(taskId, timer.elapsedTime / 1000, `/tasks/${taskId}/pause-timer`);
            }
        }

        function updateTimer(taskId) {
            const timer = timers[taskId];
            if (timer && timer.running) {
                const currentTime = new Date().getTime() - timer.startTime;
                timer.elapsedTime = currentTime;
                
                updateTimerDisplay(taskId);
                
                setTimeout(() => updateTimer(taskId), 1000);
            }
        }

        function updateTimerDisplay(taskId) {
            const timer = timers[taskId];
            if (timer) {
                const totalSeconds = Math.floor(timer.elapsedTime / 1000);
                const hours = Math.floor(totalSeconds / 3600);
                const minutes = Math.floor((totalSeconds % 3600) / 60);
                const seconds = totalSeconds % 60;
                
                document.getElementById(`time-${taskId}`).innerText = 
                    `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            }
        }

        function sendTimerUpdate(taskId, elapsedTime, url) {
            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                },
                body: JSON.stringify({ elapsed_time: elapsedTime })
            })
            .then(response => response.json())
            .then(data => console.log(`Timer updated: ${data.message}`))
            .catch(error => console.error('Error updating timer:', error));
        }

      
   // Prospect Task Timer Ends 
       
   


    </script>
</main>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontends.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\Oct 29- Live edited-project management\resources\views/frontends/dashboard.blade.php ENDPATH**/ ?>