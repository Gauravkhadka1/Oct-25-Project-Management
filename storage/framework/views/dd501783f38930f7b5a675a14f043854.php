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


        <div class="task-board">
            <?php
            use App\Models\User;
            $users = User::all();
            use App\Models\Clients;
            $clients = Clients::all();
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
            // Add tasks from clients
            foreach ($clients as $client) {
                foreach ($client->client_tasks as $task) {
                    $task->category = 'Client';
                    $task->category_name = $client->company_name; // Store client company name in 'category_name'
                    $tasks->push($task);
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
            $columnNames = [
            'To Do' => $tasksToDo,
            'In Progress' => $tasksInProgress,
            'QA' => $tasksQA,
            'Completed' => $tasksCompleted,
            'Closed' => $tasksClosed
            ];
           
            ?>

            <?php $__currentLoopData = $columnNames; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status => $tasksCollection): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="task-column" id="<?php echo e(strtolower(str_replace(' ', '', $status))); ?>" data-status="<?php echo e($status); ?>">
                <div class="status-add-new">
                    <div class="<?php echo e(strtolower(str_replace(' ', '', $status))); ?>-heading">
                        <img src="<?php echo e(url('frontend/images/' . strtolower(str_replace(' ', '', $status)) . '.png')); ?>" alt="">
                        <h3><?php echo e($status); ?></h3>
                    </div>
                    <div class="add-new-task">
                    <button class="btn-create-new" onclick="openAddTaskModal('<?php echo e(strtolower(str_replace(' ', '', $status))); ?>')">
                        <img src="frontend/images/add-new.png" alt="">
                    </button>

                    </div>
                </div>
                <div id="add-task-modal" class="hidden">
                    <form action="<?php echo e(route('clientstasks.store')); ?>" method="POST" class="custom-form">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" id="task-status" name="status" value="">
                        <div class="task-name">
                            <input type="text" id="task-name" name="name" class="task-input" placeholder="Task Name" required />
                            <button type="submit" class="btn-save-task">Save</button>
                        </div>
                        <div class="in">
                            <img src="" alt="">
                           <!-- Dropdown for Client Selection -->
                            <div class="custom-dropdown">
                                <!-- Search Bar -->
                                <input type="text" id="project-search" class="task-input" placeholder="Search clients..." onkeyup="searchClients()" style="display:none;" />

                                <!-- Dropdown Label -->
                                <div id="project-dropdown" class="dropdown-label" onclick="toggleDropdown()">
                                    <span id="selected-project">Select Client</span>
                                </div>

                                <!-- List of Clients -->
                                <div id="project-list" class="dropdown-list" style="display:none;">
                                    <?php $__currentLoopData = $clients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="dropdown-item" data-id="<?php echo e($client->id); ?>" onclick="selectClient('<?php echo e($client->id); ?>', '<?php echo e($client->company_name); ?>')">
                                        <?php echo e($client->company_name); ?>

                                    </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>

                            <!-- Hidden Input for Selected Client ID -->
                            <input type="hidden" id="client-id" name="client_id">

                        </div>


                        <div class="assigne">
                            <img src="<?php echo e(url('frontend/images/assignedby.png')); ?>" alt="">
                            <select id="assigned-to" name="assigned_to" class="task-input" required>
                                <option value="">Assign to</option>
                                <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($user->id); ?>"><?php echo e($user->username); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="due-date">
                            <img src="<?php echo e(url('frontend/images/duedate.png')); ?>" alt="">
                            <input type="date" id="due-date" name="due_date" class="task-input" required />
                        </div>
                        <div class="priority">
                            <img src="<?php echo e(url('frontend/images/priority.png')); ?>" alt="">
                            <select id="priority" name="priority" class="task-input" required>
                                <option value="Normal">Normal</option>
                                <option value="High">High</option>
                                <option value="Urgent">Urgent</option>
                            </select>
                        </div>
                    </form>
                </div>

                <div class="task-list">
                    <?php if($tasksCollection->isEmpty()): ?>
                    <div class="no-tasks" style="height: 40px; background-color: white; display: flex; align-items: center; justify-content: center; border:none; margin-top: 10px;">
                        <p>No task in <?php echo e($status); ?></p>
                    </div>

                    <?php else: ?>
                    <?php $__currentLoopData = $tasksCollection; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="task" draggable="true" data-task-id="<?php echo e($task->id); ?>" data-task-type="<?php echo e(strtolower($task->category)); ?>">
                        <div class="task-name">
                        <?php if($task->category == 'Payment'): ?>
    <a href="<?php echo e(route('payment_task.detail', ['id' => $task->id])); ?>">
<?php elseif($task->category == 'Prospect'): ?>
    <a href="<?php echo e(route('prospect_task.detail', ['id' => $task->id])); ?>">
<?php elseif($task->category == 'Client'): ?>
    <a href="<?php echo e(route('client_task.detail', ['id' => $task->id])); ?>">
<?php else: ?>
    <a href="<?php echo e(route('task.detail', ['id' => $task->id])); ?>">
<?php endif; ?>

                                        <p><?php echo e($task->name); ?></p>
                                    </a>
                        </div>
                        <div class="in-project">in <?php echo e($task->category_name); ?></div>
                        <div class="assigne">
                            <?php if($task->assignedBy): ?>
                            <img src="<?php echo e(url('frontend/images/assignedby.png')); ?>" alt="">
                            by: <img src="<?php echo e(asset('storage/profile_pictures/' . $task->assignedBy->profilepic)); ?>"
                                alt="<?php echo e($task->assignedBy->username); ?>'s Profile Picture" class="profile-pic" id="assigned-pic"> <?php echo e($task->assignedBy->username); ?>

                            <?php else: ?>
                            <img src="<?php echo e(url('frontend/images/unassigned.png')); ?>" alt="Unassigned">
                            by: N/A
                            <?php endif; ?>
                        </div>
                        <div class="due-date">
                            <img src="<?php echo e(url('frontend/images/duedate.png')); ?>" alt="">
                            : <?php echo e($task->due_date); ?>

                        </div>
                        <div class="priority">
                            <img src="<?php echo e(url('frontend/images/priority.png')); ?>" alt="">
                            : <?php echo e($task->priority); ?>

                        </div>
                        <div class="time-details">
                            <div class="start-pause">
                                <button class="btn-toggle start" id="toggle-<?php echo e($task->id); ?>" onclick="toggleTimer(<?php echo e($task->id); ?>, '<?php echo e($task->category); ?>')">
                                    <img src="<?php echo e(url('frontend/images/play.png')); ?>" alt="">
                                </button>
                            </div>
                            <div class="time-data" id="time-<?php echo e($task->id); ?>">00:00:00</div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
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

// Initialize timers for Clients tasks

<?php $__currentLoopData = $clients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<?php $__currentLoopData = $client->client_tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
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
    buttonImage.src = "<?php echo e(url('frontend/images/play.png')); ?>"; // Show play icon
    button.classList.remove("pause");
    button.classList.add("start");
} else {
    // If timer is paused or not started, start/resume it
    startTimer(taskId, taskCategory);
    buttonImage.src = "<?php echo e(url('frontend/images/pause.png')); ?>"; // Show pause icon
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

// Enable drag-and-drop
tasks.forEach(task => {
task.addEventListener('dragstart', () => {
task.classList.add('dragging');
});

task.addEventListener('dragend', () => {
task.classList.remove('dragging');
});
});

// Update task status on drop
columns.forEach(column => {
column.addEventListener('dragover', (e) => {
e.preventDefault();
});

column.addEventListener('drop', (e) => {
e.preventDefault();
const draggingTask = document.querySelector('.dragging');
const taskId = draggingTask.getAttribute('data-task-id');
const taskType = draggingTask.getAttribute('data-task-type');
const newStatus = column.getAttribute('data-status');

// Move task to new column
column.querySelector('.task-list').appendChild(draggingTask);

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


// Open Add Task Modal

let currentOpenModal = null;  // Track the currently open modal

function openAddTaskModal(columnId) {
    const modal = document.getElementById('add-task-modal');
    if (!modal) return;

    // Close the existing modal if it's open
    if (currentOpenModal && currentOpenModal !== modal) {
        closeAddTaskModal();
    }

    // Move the modal to the correct column
    const column = document.getElementById(columnId);
    const taskList = column.querySelector('.task-list');
    if (taskList) {
        taskList.insertBefore(modal, taskList.firstChild);
    }

    // Update the hidden input field with the column's status
    const columnField = document.getElementById('task-status');
    if (columnField) {
        columnField.value = column.getAttribute('data-status');  // Ensure it uses the correct status from the column
    }

    // Show the modal
    modal.classList.remove('hidden');
    modal.style.display = 'block';

    // Set the current open modal to prevent multiple modals in one column
    currentOpenModal = modal;

    // Add event listener to close the modal when clicking outside
    setTimeout(() => document.addEventListener('click', handleOutsideClick), 0);
}

function closeAddTaskModal() {
    const modal = document.getElementById('add-task-modal');
    if (!modal) return;

    modal.classList.add('hidden');
    modal.style.display = 'none';

    // Reattach the modal to the body to ensure it's not stuck in one column
    const body = document.querySelector('body');
    body.appendChild(modal);

    // Reset the modal fields
    document.getElementById('task-name').value = '';
    document.getElementById('assigned-to').value = '';
    document.getElementById('due-date').value = '';
    document.getElementById('priority').value = 'Normal';
    document.getElementById('task-status').value = '';

    // Clear the current open modal reference
    currentOpenModal = null;

    // Remove event listener for outside clicks
    document.removeEventListener('click', handleOutsideClick);
}

function handleOutsideClick(event) {
    const modal = document.getElementById('add-task-modal');
    const isClickInsideModal = modal.contains(event.target);
    const isClickInsideColumn = currentOpenModal && document.getElementById(currentOpenModal.id).contains(event.target);

    // Close the modal if the click is outside the modal and the column
    if (!isClickInsideModal && !isClickInsideColumn) {
        closeAddTaskModal();
    }
}




// Save Task and Update Column
function saveTask() {
    const taskName = document.getElementById('task-name').value;
    const assignedTo = document.getElementById('assigned-to').value;
    const dueDate = document.getElementById('due-date').value;
    const priority = document.getElementById('priority').value;
    const status = document.getElementById('task-status').value;

    console.log(`Saving task with status: ${status}`); // Debugging: log the status being sent

    if (!taskName || !assignedTo || !dueDate || !priority || !status) {
        alert('Please fill in all fields!');
        return;
    }

    const data = {
        name: taskName,
        assigned_to: assignedTo,
        due_date: dueDate,
        priority: priority,
        status: status,
        _token: '<?php echo e(csrf_token()); ?>',
    };

    fetch('<?php echo e(route('tasks.store')); ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data),
    })
    .then(response => {
        if (!response.ok) throw new Error('Failed to save task.');
        return response.json();
    })
    .then(task => {
        console.log('Task saved successfully:', task); // Debugging: log the saved task
        const taskList = document.querySelector(`#${status} .task-list`);
        if (taskList) {
            const newTask = `
                <div class="task" draggable="true" data-task-id="${task.id}">
                    <div class="task-name">
                        <a href="">
                            <p>${task.name}</p>
                        </a>
                    </div>
                    <div class="assigne">
                        <img src="<?php echo e(url('frontend/images/assignedby.png')); ?>" alt=""> to: ${task.assigned_to_username}
                    </div>
                    <div class="due-date">
                        <img src="<?php echo e(url('frontend/images/duedate.png')); ?>" alt=""> : ${task.due_date}
                    </div>
                    <div class="priority">
                        <img src="<?php echo e(url('frontend/images/priority.png')); ?>" alt=""> : ${task.priority}
                    </div>
                </div>
            `;
            taskList.insertAdjacentHTML('afterbegin', newTask);
        }

        closeAddTaskModal();

        // Reset the modal fields
        document.getElementById('task-name').value = '';
        document.getElementById('assigned-to').value = '';
        document.getElementById('due-date').value = '';
        document.getElementById('priority').value = 'Normal';
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to save task. Please try again.');
    });
}



// Toggle dropdown visibility
// Toggle dropdown visibility
function toggleDropdown() {
    const projectList = document.getElementById('project-list');
    const searchInput = document.getElementById('project-search');

    // Toggle visibility of the project list and search bar
    projectList.style.display = projectList.style.display === 'none' ? 'block' : 'none';
    searchInput.style.display = searchInput.style.display === 'none' ? 'block' : 'none';
}

// Select a client and update the label
function selectClient(clientId, clientName) {
    // Update the hidden input with the selected client's ID
    document.getElementById('client-id').value = clientId;

    // Update the dropdown label with the selected client's name
    document.getElementById('selected-project').textContent = clientName;

    // Hide the dropdown list and search bar
    document.getElementById('project-list').style.display = 'none';
    document.getElementById('project-search').style.display = 'none';
    
    // Reset search input value after client selection
    document.getElementById('project-search').value = '';
}

// Filter the client list based on search input
function searchClients() {
    const input = document.getElementById('project-search').value.toLowerCase();
    const items = document.getElementsByClassName('dropdown-item');

    // Loop through the items and display/hide based on match
    Array.from(items).forEach(item => {
        const projectName = item.textContent.toLowerCase();
        item.style.display = projectName.includes(input) ? 'block' : 'none';
    });
}




    </script>



    <style>
    #add-task-modal {
    position: relative; /* Align within its new column */
    z-index: 1000;
    background: white;
    border: 1px solid #ccc;
    border-radius: 5px;
    padding: 20px;
    margin-bottom: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.hidden {
    display: none !important;
}




        .task-input {
            width: calc(100% - 30px);
            padding: 2px;
            margin: 5px 0;
            border: none;
            background-color: transparent;
            border-radius: 4px;
        }

        .task-actions {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
        }

        .btn-save-task,
        .btn-cancel-task {
            padding: 5px 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .btn-save-task {
            background-color: #007bff;
            color: white;
        }

        .btn-save-task:hover {
            background-color: #002aee !important;
            color: white;
        }

        .btn-cancel-task {
            background-color: #dc3545;
            color: white;
        }

        /* select in project style  */
        /* Container for the custom dropdown */
.custom-dropdown {
    position: relative;
}

/* The dropdown label (button) */
.dropdown-label {
    padding: 5px;
    margin: 5px;
    border: none;
    border-radius: 10px;
    cursor: pointer;
    background-color: #fff;
}

/* The search input box */
#project-search {
    width: 100%;
    padding: 8px;
    margin-bottom: 5px;
    border: 1px solid #ccc;
    border-radius: 4px;
}

/* The dropdown list container */
.dropdown-list {
    position: absolute;
    width: 100%;
    border: 1px solid #ccc;
    max-height: 200px;
    overflow-y: auto;
    background-color: white;
    z-index: 999;
}

/* The individual project items */
.dropdown-item {
    padding: 10px;
    cursor: pointer;
}

.dropdown-item:hover {
    background-color: #f0f0f0;
}

    </style>
</main>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('frontends.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\Oct 29- Live edited-project management\resources\views/frontends/dashboard.blade.php ENDPATH**/ ?>