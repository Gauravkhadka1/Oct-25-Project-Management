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
        
        <div class="your-task">
            <p><?php echo e($loggedInUser); ?>- Task's</p> 
        </div>


        <div class="task-board">
    <?php
    use Carbon\Carbon;

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

        // Sort tasks by the remaining time to due date
        $tasks = $tasks->sortBy(function ($task) {
        return Carbon::parse($task->due_date); // Parse and return the Carbon instance for accurate sorting
    });

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
                        <div class="task-name-new">
                            <?php if($task->category == 'Payment'): ?>
                                <a href="<?php echo e(route('payment_task.detail', ['id' => $task->id])); ?>">
                            <?php elseif($task->category == 'Prospect'): ?>
                                <a href="<?php echo e(route('prospect_task.detail', ['id' => $task->id])); ?>">
                            <?php else: ?>
                                <a href="<?php echo e(route('task.detail', ['id' => $task->id])); ?>">
                            <?php endif; ?>
                                <p><?php echo e($task->name); ?></p>
                            </a>
                            
                            <a href="<?php echo e($task->driveurl); ?>" target="_blank" class="task-icon-icon">
                                <img src="<?php echo e(url('public/frontend/images/google-drive.png')); ?>" alt="Google Drive">
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
                    <form method="GET" action="<?php echo e(route('dashboard')); ?>">
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
                        <?php if(count($tasks) === 1 && $tasks->first()['task_name'] === 'N/A'): ?>
                            <tr>
                                <td><?php echo e($interval); ?></td>
                                <td colspan="3">No tasks during this interval</td>
                            </tr>
                            <?php else: ?>
                                <?php $isFirstTask = true; ?>
                                <?php $__currentLoopData = $tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($isFirstTask ? $interval : ''); ?></td>
                                        <td><?php echo e($task['task_name']); ?></td>
                                        <td><?php echo e($task['project_name']); ?></td>
                                        <td><?php echo e($task['time_spent']); ?></td>
                                    </tr>
                                    <?php $isFirstTask = false; ?>
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
    const buttonImage = button.querySelector('img'); // Get the image inside the button

    if (timer) {
        if (timer.running) {
            // If timer is running, pause it
            pauseTimer(taskId, taskCategory);
            buttonImage.src = "<?php echo e(url('public/frontend/images/play.png')); ?>"; // Show play icon
            button.classList.remove("pause");
            button.classList.add("start");
        } else {
            // If timer is paused or not started, start/resume it
            startTimer(taskId, taskCategory);
            buttonImage.src = "<?php echo e(url('public/frontend/images/pause.png')); ?>"; // Show pause icon
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
            task_category: taskCategory // Ensure correct category is sent
        })
    })
    .then(response => response.json())
    .then(data => {
        console.log("Timer updated:", data);
    })
    .catch(error => {
        console.error("Error updating timer:", error);
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
         // JavaScript for drag-and-drop functionality
         const tasks = document.querySelectorAll('.task');
const columns = document.querySelectorAll('.task-column');
let placeholder; // Placeholder element to indicate where the task can be dropped

// Enable drag-and-drop
tasks.forEach(task => {
    task.addEventListener('dragstart', () => {
        task.classList.add('dragging');

        // Create a placeholder with the same height as the dragging task
        placeholder = document.createElement('div');
        placeholder.classList.add('placeholder');
        placeholder.style.height = `${task.offsetHeight}px`;
    });

    task.addEventListener('dragend', () => {
        task.classList.remove('dragging');

        // Remove the placeholder when drag ends
        if (placeholder && placeholder.parentNode) {
            placeholder.parentNode.removeChild(placeholder);
        }
    });
});

// Handle dragover and drop in columns
columns.forEach(column => {
    column.addEventListener('dragover', (e) => {
        e.preventDefault();

        const draggingTask = document.querySelector('.dragging');
        const taskList = column.querySelector('.task-list');
        const tasksInColumn = [...taskList.querySelectorAll('.task:not(.dragging)')];

        // Find the nearest task where the placeholder should be inserted
        const afterTask = tasksInColumn.find(task => {
            const taskRect = task.getBoundingClientRect();
            return e.clientY < taskRect.top + taskRect.height / 2;
        });

        // Insert placeholder
        if (afterTask) {
            taskList.insertBefore(placeholder, afterTask);
        } else {
            taskList.appendChild(placeholder);
        }
    });

    column.addEventListener('drop', (e) => {
        e.preventDefault();

        const draggingTask = document.querySelector('.dragging');
        const taskId = draggingTask.getAttribute('data-task-id');
        const taskType = draggingTask.getAttribute('data-task-type');
        const newStatus = column.getAttribute('data-status');

        // Move the dragging task to the placeholder position
        placeholder.parentNode.replaceChild(draggingTask, placeholder);

        // AJAX request to update task status in the database
        fetch("<?php echo e(route('tasks.updateStatusComment')); ?>", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': "<?php echo e(csrf_token()); ?>"
            },
            body: JSON.stringify({ taskId, taskType, status: newStatus })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log(`Task ${taskId} status updated to ${newStatus}`);
            } else {
                console.error("Failed to update task status");
            }
        })
        .catch(error => console.error("Error:", error));
    });
});


   


    </script>
    <style>
        .your-task {
  width: 100%;
  margin-top: -25px;
  margin-bottom: 10px;
  padding-left: 20px;
  font-size: 18px;
  font-weight: 525;
}
.placeholder {
    background: #fff;
    margin: 5px 0;
    border-radius: 5px;
}
.status-n-count-dashboard {
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.task-count-dashboard {
    margin-right: 15px;
    font-weight: 500;
}
.task-name-new {
        display: flex;
        align-items: center;
        justify-content: space-between !important;
        /* background-color: red; */
    }
    .task-name-new a {
        text-decoration: none;
        color: #2a2e34;
        font-size: 14px;
        font-weight: 500;
    }
    .task-name-new a:hover {
        text-decoration: underline;
    }
    .task-name-new img {
        margin-right: 10px !important;
        width: 15px;
    }
    </style>
</main>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontends.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\Oct 29- Live edited-project management\resources\views/frontends/dashboard.blade.php ENDPATH**/ ?>