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
                    <span class="<?php echo e($task->category === 'Project' ? 'label-project' : 
                                 ($task->category === 'Payment' ? 'label-payment' : 'label-prospect')); ?>">
                        <?php echo e($task->category); ?>

                    </span>
                </td>
                <td><?php echo e($task->assignedBy ? $task->assignedBy->username : 'N/A'); ?></td>
                <td><?php echo e($task->start_date); ?></td>
                <td><?php echo e($task->due_date); ?></td>
                <td><?php echo e($task->priority); ?></td>
                <td>
                <button class="btn-toggle start" id="toggle-<?php echo e($task->id); ?>" onclick="toggleTimer(<?php echo e($task->id); ?>, '<?php echo e($task->category); ?>')">Start</button>


                </td>
                <td id="time-<?php echo e($task->id); ?>">00:00:00</td>

                <!-- Status Column -->
                <td>
                    <?php if($task->category == 'Project'): ?>
                        <select name="status[<?php echo e($task->id); ?>]" class="task-status">
                            <option <?php echo e($task->status === 'To Do' ? 'selected' : ''); ?>>To Do</option>
                            <option <?php echo e($task->status === 'In Progress' ? 'selected' : ''); ?>>In Progress</option>
                            <option <?php echo e($task->status === 'QA' ? 'selected' : ''); ?>>QA</option>
                            <option <?php echo e($task->status === 'Completed' ? 'selected' : ''); ?>>Completed</option>
                        </select>
                    <?php elseif($task->category == 'Payment'): ?>
                        <select name="status[<?php echo e($task->id); ?>]" class="payment-task-status">
                            <option <?php echo e($task->status === 'To Do' ? 'selected' : ''); ?>>To Do</option>
                            <option <?php echo e($task->status === 'In Progress' ? 'selected' : ''); ?>>In Progress</option>
                            <option <?php echo e($task->status === 'QA' ? 'selected' : ''); ?>>QA</option>
                            <option <?php echo e($task->status === 'Completed' ? 'selected' : ''); ?>>Completed</option>
                        </select>
                    <?php elseif($task->category == 'Prospect'): ?>
                        <select name="status[<?php echo e($task->id); ?>]" class="prospect-task-status">
                            <option <?php echo e($task->status === 'To Do' ? 'selected' : ''); ?>>To Do</option>
                            <option <?php echo e($task->status === 'In Progress' ? 'selected' : ''); ?>>In Progress</option>
                            <option <?php echo e($task->status === 'QA' ? 'selected' : ''); ?>>QA</option>
                            <option <?php echo e($task->status === 'Completed' ? 'selected' : ''); ?>>Completed</option>
                        </select>
                    <?php endif; ?>
                </td>

                <!-- Comment Column -->
                <td>
                    <?php if($task->category == 'Project'): ?>
                        <textarea name="comment[<?php echo e($task->id); ?>]" class="task-comment"><?php echo e($task->comment); ?></textarea>
                    <?php elseif($task->category == 'Payment'): ?>
                        <textarea name="comment[<?php echo e($task->id); ?>]" class="payment-task-comment"><?php echo e($task->comment); ?></textarea>
                    <?php elseif($task->category == 'Prospect'): ?>
                        <textarea name="comment[<?php echo e($task->id); ?>]" class="prospect-task-comment"><?php echo e($task->comment); ?></textarea>
                    <?php endif; ?>
                </td>
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
        </tfoot>
    </table>
</div>



    
    
    </div>


    <script>
        

        let timers = {};
        // Detect page unload or navigation
window.addEventListener("beforeunload", function (e) {
    // Check if any timer is running
    const isAnyTimerRunning = Object.values(timers).some(timer => timer.running);

    // If a timer is running, show a confirmation prompt
    if (isAnyTimerRunning) {
        // Custom message for modern browsers (this is mostly ignored now)
        const message = "Please pause the time before going to another page.";
        e.returnValue = message;  // Standard for modern browsers
        return message;  // For some older browsers (rarely used now)
    }
});


        // Load the saved time from the database when the page loads
        window.addEventListener("load", () => {
    // Initialize timers for Project tasks
    <?php $__currentLoopData = $projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php $__currentLoopData = $project->tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            timers[<?php echo e($task->id); ?>] = {
                elapsedTime: <?php echo e($task->elapsed_time * 1000); ?>,
                running: false
            };
            console.log("Timer initialized for project task ID:", <?php echo e($task->id); ?>);
            updateTimerDisplay(<?php echo e($task->id); ?>);
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    // Initialize timers for Payment tasks
    <?php $__currentLoopData = $payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php $__currentLoopData = $payment->payment_tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            timers[<?php echo e($task->id); ?>] = {
                elapsedTime: <?php echo e($task->elapsed_time * 1000); ?>,
                running: false
            };
            console.log("Timer initialized for payment task ID:", <?php echo e($task->id); ?>);
            updateTimerDisplay(<?php echo e($task->id); ?>);
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    // Initialize timers for Prospect tasks
    <?php $__currentLoopData = $prospects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prospect): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php $__currentLoopData = $prospect->prospect_tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            timers[<?php echo e($task->id); ?>] = {
                elapsedTime: <?php echo e($task->elapsed_time * 1000); ?>,
                running: false
            };
            console.log("Timer initialized for prospect task ID:", <?php echo e($task->id); ?>);
            updateTimerDisplay(<?php echo e($task->id); ?>);
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
});



        // Modify toggleTimer to ensure that it pauses the timer before leaving
        function toggleTimer(taskId, taskCategory) {
    const timer = timers[taskId];
    const button = document.getElementById(`toggle-${taskId}`);

    if (timer) {
        if (timer.running) {
            // If timer is running, pause it
            pauseTimer(taskId, taskCategory);
            button.innerText = "Resume";
            button.classList.remove("pause");
            button.classList.add("start");
        } else {
            // If timer is paused or not started, start/resume it
            startTimer(taskId, taskCategory);
            button.innerText = "Pause";
            button.classList.remove("start");
            button.classList.add("pause");
        }
    } else {
        console.error(`Timer not found for task ID: ${taskId}`);
    }
}


function startTimer(taskId, taskCategory) {
    const timer = timers[taskId];
    if (timer) {
        timer.startTime = new Date().getTime() - timer.elapsedTime;
        timer.running = true;
        
        console.log(`Starting/resuming timer for task ID: ${taskId} of category ${taskCategory}`);
        sendTimerUpdate(taskId, timer.elapsedTime / 1000, `/tasks/${taskId}/start-timer`, taskCategory);
        
        updateTimer(taskId);
    }
}

function pauseTimer(taskId, taskCategory) {
    const timer = timers[taskId];
    if (timer && timer.running) {
        timer.elapsedTime = new Date().getTime() - timer.startTime;
        timer.running = false;
        
        console.log(`Pausing timer for task ${taskId} of category ${taskCategory}, elapsed time: ${timer.elapsedTime}`);
        
        sendTimerUpdate(taskId, timer.elapsedTime / 1000, `/tasks/${taskId}/pause-timer`, taskCategory);
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
        function sendTimerUpdate(taskId, elapsedTime, url, taskCategory) {
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            elapsed_time: elapsedTime,
            task_category: taskCategory
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log("Timer updated:", data);
    })
    .catch(error => {
        console.error("Error updating timer:", error);
        // Additional error handling if needed
    });
}


        // Handle status change for task, payment task, and prospect task
document.querySelectorAll('.task-status, .payment-task-status, .prospect-task-status').forEach(statusElement => {
    statusElement.addEventListener('change', function () {
        const taskId = this.name.match(/\d+/)[0];
        const status = this.value;
        const taskType = this.classList.contains('payment-task-status') ? 'payment' : 
                         this.classList.contains('prospect-task-status') ? 'prospect' : 'task'; // Determine the task type

        fetch(`/tasks/update-status-comment`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Ensure CSRF token is correct
            },
            body: JSON.stringify({ taskId, status, taskType }) // Add taskType to distinguish between task types
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log('Status updated successfully.');
            } else {
                console.error('Failed to update status.');
            }
        })
        .catch(error => console.error('Error:', error));
    });
});

// Handle comment change for task, payment task, and prospect task
document.querySelectorAll('.task-comment, .payment-task-comment, .prospect-task-comment').forEach(commentElement => {
    commentElement.addEventListener('change', function () {
        const taskId = this.name.match(/\d+/)[0];
        const comment = this.value;
        const taskType = this.classList.contains('payment-task-comment') ? 'payment' : 
                         this.classList.contains('prospect-task-comment') ? 'prospect' : 'task'; // Determine the task type

        fetch(`/tasks/update-comment`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Ensure CSRF token is correct
            },
            body: JSON.stringify({ task_id: taskId, comment, taskType }) // Add taskType to distinguish between task types
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log('Comment updated successfully.');
            } else {
                console.error('Failed to update comment.');
            }
        })
        .catch(error => console.error('Error:', error));
    });
});


      
   // Prospect Task Timer Ends 
       
   


    </script>
</main>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontends.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\Oct 29- Live edited-project management\resources\views/frontends/dashboard.blade.php ENDPATH**/ ?>