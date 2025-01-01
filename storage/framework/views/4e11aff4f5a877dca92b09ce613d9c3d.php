<?php $__env->startSection('main-container'); ?>

<?php if(session('success')): ?>
<div id="success-message" style="position: fixed; top: 20%; left: 50%; transform: translate(-50%, -50%); background-color: #28a745; color: white; padding: 15px; border-radius: 5px; z-index: 9999;">
    <?php echo e(session('success')); ?>

</div>

<?php endif; ?>

<div id="success-message" style="display: none; position: fixed; top: 20%; left: 50%; transform: translate(-50%, -50%); background-color: #28a745; color: white; padding: 15px; border-radius: 5px; z-index: 9999;">
    <!-- Success message will be dynamically inserted here -->
</div>
<div class="project-name-status">
<div class="project-n">
    <p><?php echo e($project->name); ?> Tasks</p>
</div>


</div>
<div class="task-board" id="project-details-page" >
    <!-- Column for To Do tasks -->
    <div class="task-column" id="todo" data-status="TO DO">
        <div class="todo-add">
        <div class="todo-heading">
                    <img src="<?php echo e(url ('public/frontend/images/todo.png')); ?>" alt="">
                    <h3>TO DO</h3>
                </div>
            <div class="add-icon">
                <button class="btn-create-new" id="task-create" onclick="openAddTaskModal(<?php echo e($project->id); ?>)">
                    <img src="<?php echo e(url('public/frontend/images/add-new.png')); ?>" alt="" style="width: 15px; margin-right:10px;">
                </button>
            </div>
        </div>
  <!-- Add Task Form -->
  <div id="add-task-modal" class="hidden">
        <form action="<?php echo e(route('tasks.store')); ?>" method="POST" class="custom-form">
            <?php echo csrf_field(); ?>
            <input type="hidden" id="project-id" name="project_id" value="">

            <div class="task-name">
                <input type="text" id="task-name" name="name" class="task-input" placeholder="Task Name" required />
                <button type="submit" class="btn-save-task">Save</button>
            </div>
            <div class="assigne">
                <img src="<?php echo e(url('public/frontend/images/assignedby.png')); ?>" alt="">
                <select id="assigned-to" name="assigned_to" class="task-input" required>
                    <option value="">Assign to</option>
                    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($user->id); ?>"><?php echo e($user->username); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="project-start-date">
    <div class="start-date-input">
        <img src="<?php echo e(url('public/frontend/images/start-date.png')); ?>" alt="">
        <input type="text" id="start-date" name="start_date" class="task-input" placeholder="Start Date" readonly required />
    </div>
</div>

<div class="project-due-date">
    <div class="due-date-input">
        <img src="<?php echo e(url('public/frontend/images/end-date.png')); ?>" alt="">
        <input type="text" id="due-date" name="due_date" class="task-input" placeholder="Due Date" readonly required />
    </div>
</div>

            <div class="priority">
                <img src="<?php echo e(url('public/frontend/images/priority.png')); ?>" alt="">
                <select id="priority" name="priority" class="task-input" required>
                    <option value="Normal">Normal</option>
                    <option value="High">High</option>
                    <option value="Urgent">Urgent</option>
                </select>
            </div>
            <div class="drive">
            <img src="<?php echo e(url('public/frontend/images/google-drive.png')); ?>" alt="">
            <input type="text" id="driveurl" name="driveurl" class="task-input" placeholder="Drive Link"/>
            </div>
            
            <!-- <div class="task-actions">
            
                <button type="button" class="btn-cancel-task" onclick="toggleAddTaskForm()">Cancel</button>
            </div> -->
        </form>

     </div>
        <!-- Task List -->
        <div class="task-list">
            <?php $__currentLoopData = $todoTasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="task" draggable="true">
                    <div class="task-name-new">
                       <a href="<?php echo e(route('task.detail', ['id' => $task->id])); ?>" class="task-name-name">
                            <p><?php echo e($task->name); ?></p>
                        </a>
                        <?php if(!empty($task->driveurl)): ?>
    <a href="<?php echo e($task->driveurl); ?>" target="_blank" class="task-icon-icon">
        <img src="<?php echo e(url('public/frontend/images/google-drive.png')); ?>" alt="Google Drive">
    </a>
<?php endif; ?>

                    </div>
                    
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
                    <div class="due-date" style="margin-top: 4px;">
    <img src="<?php echo e(url('public/frontend/images/due-date.png')); ?>" alt=""> :
    <?php if(isset($task->remaining_days) || isset($task->remaining_hours) || isset($task->overdue_days) || isset($task->overdue_hours)): ?>
        <?php if(isset($task->remaining_days) && $task->remaining_days > 0): ?>
            <?php echo e($task->remaining_days); ?> days <?php echo e($task->remaining_hours); ?> hours left
        <?php elseif(isset($task->remaining_hours) && $task->remaining_hours > 0): ?>
            <?php echo e($task->remaining_hours); ?> hours left
        <?php elseif(isset($task->overdue_days) && $task->overdue_days > 0): ?>
            Overdue by <?php echo e($task->overdue_days); ?> days <?php echo e($task->overdue_hours); ?> hours
        <?php elseif(isset($task->overdue_hours) && $task->overdue_hours > 0): ?>
            Overdue by <?php echo e($task->overdue_hours); ?> hours
        <?php else: ?>
            Due now
        <?php endif; ?>
    <?php else: ?>
        N/A
    <?php endif; ?>
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
                    <!-- <div class="priority">-->
                    <!--Time spent: <?php echo e(gmdate('H:i:s', $task->elapsed_time)); ?>-->

                    <!--</div>-->
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>

    <!-- Column for In Progress tasks -->
    <div class="task-column" id="in-progress" data-status="IN PROGRESS">
        <div class="todo-add">
        <div class="inprogress-heading">
                    <img src="<?php echo e(url ('public/frontend/images/inprogress.png')); ?>" alt="">
                    <h3>IN PROGRESS</h3>
                </div>
            
        </div>

        <!-- Task List -->
        <div class="task-list">
            <?php $__currentLoopData = $inProgressTasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="task" draggable="true">
                <div class="task-name-new">
                       <a href="<?php echo e(route('task.detail', ['id' => $task->id])); ?>" class="task-name-name">
                            <p><?php echo e($task->name); ?></p>
                        </a>
                        <a href="<?php echo e($task->driveurl); ?>" target="_blank" class="task-icon-icon">
                <img src="<?php echo e(url('public/frontend/images/google-drive.png')); ?>" alt="Google Drive">
            </a>
                    </div>
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
                    <div class="due-date" style="margin-top: 4px;">
    <img src="<?php echo e(url('public/frontend/images/due-date.png')); ?>" alt=""> :
    <?php if(isset($task->remaining_days) || isset($task->remaining_hours) || isset($task->overdue_days) || isset($task->overdue_hours)): ?>
        <?php if(isset($task->remaining_days) && $task->remaining_days > 0): ?>
            <?php echo e($task->remaining_days); ?> days <?php echo e($task->remaining_hours); ?> hours left
        <?php elseif(isset($task->remaining_hours) && $task->remaining_hours > 0): ?>
            <?php echo e($task->remaining_hours); ?> hours left
        <?php elseif(isset($task->overdue_days) && $task->overdue_days > 0): ?>
            Overdue by <?php echo e($task->overdue_days); ?> days <?php echo e($task->overdue_hours); ?> hours
        <?php elseif(isset($task->overdue_hours) && $task->overdue_hours > 0): ?>
            Overdue by <?php echo e($task->overdue_hours); ?> hours
        <?php else: ?>
            Due now
        <?php endif; ?>
    <?php else: ?>
        N/A
    <?php endif; ?>
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
                    <!-- <div class="priority">-->
                    <!--Time spent: <?php echo e(gmdate('H:i:s', $task->elapsed_time)); ?>-->

                    <!--</div>-->
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>

    <!-- Column for QA tasks -->
    <div class="task-column" id="qa" data-status="QA">
        <div class="todo-add">
        <div class="qa-heading">
                    <img src="<?php echo e(url ('public/frontend/images/qa.png')); ?>" alt="">
                    <h3>QA</h3>
                </div>
           
        </div>

        <!-- Task List -->
        <div class="task-list">
            <?php $__currentLoopData = $qaTasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="task" draggable="true">
                <div class="task-name-new">
                       <a href="<?php echo e(route('task.detail', ['id' => $task->id])); ?>" class="task-name-name">
                            <p><?php echo e($task->name); ?></p>
                        </a>
                        <a href="<?php echo e($task->driveurl); ?>" target="_blank" class="task-icon-icon">
                <img src="<?php echo e(url('public/frontend/images/google-drive.png')); ?>" alt="Google Drive">
            </a>
                    </div>
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
                    <div class="due-date" style="margin-top: 4px;">
    <img src="<?php echo e(url('public/frontend/images/due-date.png')); ?>" alt=""> :
    <?php if(isset($task->remaining_days) || isset($task->remaining_hours) || isset($task->overdue_days) || isset($task->overdue_hours)): ?>
        <?php if(isset($task->remaining_days) && $task->remaining_days > 0): ?>
            <?php echo e($task->remaining_days); ?> days <?php echo e($task->remaining_hours); ?> hours left
        <?php elseif(isset($task->remaining_hours) && $task->remaining_hours > 0): ?>
            <?php echo e($task->remaining_hours); ?> hours left
        <?php elseif(isset($task->overdue_days) && $task->overdue_days > 0): ?>
            Overdue by <?php echo e($task->overdue_days); ?> days <?php echo e($task->overdue_hours); ?> hours
        <?php elseif(isset($task->overdue_hours) && $task->overdue_hours > 0): ?>
            Overdue by <?php echo e($task->overdue_hours); ?> hours
        <?php else: ?>
            Due now
        <?php endif; ?>
    <?php else: ?>
        N/A
    <?php endif; ?>
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
                    <!-- <div class="priority">-->
                    <!--Time spent: <?php echo e(gmdate('H:i:s', $task->elapsed_time)); ?>-->

                    <!--</div>-->
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>

    <!-- Column for Completed tasks -->
    <div class="task-column" id="completed" data-status="COMPLETED">
        <div class="todo-add">
        <div class="completed-heading">
                    <img src="<?php echo e(url ('public/frontend/images/completed.png')); ?>" alt="">
                    <h3>COMPLETED</h3>
                </div>
            
        </div>

        <!-- Task List -->
        <div class="task-list">
            <?php $__currentLoopData = $completedTasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="task" draggable="true">
                <div class="task-name-new">
                       <a href="<?php echo e(route('task.detail', ['id' => $task->id])); ?>" class="task-name-name">
                            <p><?php echo e($task->name); ?></p>
                        </a>
                        <a href="<?php echo e($task->driveurl); ?>" target="_blank" class="task-icon-icon">
                <img src="<?php echo e(url('public/frontend/images/google-drive.png')); ?>" alt="Google Drive">
            </a>
                    </div>
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

<!--<div class="due-date" style="margin-top: 4px;">-->
<!--    <img src="<?php echo e(url('public/frontend/images/due-date.png')); ?>" alt=""> :-->
<!--    <?php if(isset($task->remaining_days) || isset($task->remaining_hours) || isset($task->overdue_days) || isset($task->overdue_hours)): ?>-->
<!--        <?php if(isset($task->remaining_days) && $task->remaining_days > 0): ?>-->
<!--            <?php echo e($task->remaining_days); ?> days <?php echo e($task->remaining_hours); ?> hours left-->
<!--        <?php elseif(isset($task->remaining_hours) && $task->remaining_hours > 0): ?>-->
<!--            <?php echo e($task->remaining_hours); ?> hours left-->
<!--        <?php elseif(isset($task->overdue_days) && $task->overdue_days > 0): ?>-->
<!--            Overdue by <?php echo e($task->overdue_days); ?> days <?php echo e($task->overdue_hours); ?> hours-->
<!--        <?php elseif(isset($task->overdue_hours) && $task->overdue_hours > 0): ?>-->
<!--            Overdue by <?php echo e($task->overdue_hours); ?> hours-->
<!--        <?php else: ?>-->
<!--            Due now-->
<!--        <?php endif; ?>-->
<!--    <?php else: ?>-->
<!--        N/A-->
<!--    <?php endif; ?>-->
<!--</div>-->
                    <div class="priority">
                        <img src="<?php echo e(url('public/frontend/images/priority.png')); ?>" alt=""> : <?php echo e($task->priority ?? 'Normal'); ?>

                    </div>
                    <!-- <div class="priority">-->
                    <!--Time spent: <?php echo e(gmdate('H:i:s', $task->elapsed_time)); ?>-->

                    <!--</div>-->
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</div>


<!-- JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Initialize Flatpickr for Start Date
    flatpickr("#start-date", {
        dateFormat: "Y-m-d h:i K",  // Format of the date and time with AM/PM
        allowInput: true,          // Allow manual input
        enableTime: true,          // Enable time selection
        time_24hr: false,          // Use AM/PM format
        onChange: function(selectedDates, dateStr, instance) {
            console.log("Selected start date and time: " + dateStr);
        }
    });

    // Initialize Flatpickr for Due Date
    flatpickr("#due-date", {
        dateFormat: "Y-m-d h:i K",  // Format of the date and time with AM/PM
        allowInput: true,          // Allow manual input
        enableTime: true,          // Enable time selection
        time_24hr: false,          // Use AM/PM format
        onChange: function(selectedDates, dateStr, instance) {
            console.log("Selected due date and time: " + dateStr);
        }
    });
});

// Function to open the "Add Task" modal
function openAddTaskModal(projectID) {
    document.getElementById('project-id').value = projectID; // Set project ID
    console.log('Opening modal for project ID:', projectID); // Debug log

    const modal = document.getElementById('add-task-modal');
    modal.classList.remove('hidden'); // Remove hidden class
    modal.style.display = 'block'; // Ensure display is block

    // Add event listener for outside clicks
    setTimeout(() => document.addEventListener('click', handleOutsideClick), 0);
}

// Function to close the "Add Task" modal
function closeAddTaskModal() {
    const modal = document.getElementById('add-task-modal');
    modal.classList.add('hidden'); // Add hidden class
    modal.style.display = 'none'; // Reset display

    // Remove the outside click listener
    document.removeEventListener('click', handleOutsideClick);
}

// Handle outside click to close the modal
function handleOutsideClick(event) {
    const modal = document.getElementById('add-task-modal');
    const flatpickrElements = document.querySelectorAll('.flatpickr-calendar'); // Flatpickr popups

    // Check if the click is outside the modal and not on any Flatpickr component
    const clickedOnFlatpickr = Array.from(flatpickrElements).some(el => el.contains(event.target));
    if (!modal.contains(event.target) && !event.target.closest('.custom-form') && !clickedOnFlatpickr) {
        closeAddTaskModal();
    }
}





// Function to save the task
function saveTask() {
    const taskName = document.getElementById('task-name').value;
    const assignedTo = document.getElementById('assigned-to').value;
    const dueDate = document.getElementById('due-date').value;
    const priority = document.getElementById('priority').value;
    const projectId = document.getElementById('project-id').value;

    // Validation
    if (!taskName || !assignedTo || !dueDate || !priority) {
        alert('Please fill in all fields!');
        return;
    }

    // Prepare data to send
    const data = {
        name: taskName,
        assigned_to: assignedTo,
        project_id: projectId,
        due_date: dueDate,
        priority: priority,
        _token: '<?php echo e(csrf_token()); ?>', // Include CSRF token for Laravel
    };

    // Send data to server via AJAX
    fetch('<?php echo e(route('tasks.store')); ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        },
        body: JSON.stringify(data),
    })
        .then(response => {
            if (!response.ok) {
                throw new Error('Failed to save task.');
            }
            return response.json();
        })
        .then(task => {
            // Add task to the UI
            const taskList = document.querySelector('.task-list');
            const newTask = `
                <div class="task" draggable="true">
                    <div class="task-name">
                        <a href="">
                            <p>${task.name}</p>
                        </a>
                    </div>
                    <div class="assigne">
                        <img src="<?php echo e(url ('frontend/images/assignedby.png')); ?>" alt=""> to: ${task.assigned_to_username}
                    </div>
                    <div class="due-date">
                        <img src="<?php echo e(url ('frontend/images/duedate.png')); ?>" alt=""> : ${task.due_date}
                    </div>
                    <div class="priority">
                        <img src="<?php echo e(url ('frontend/images/priority.png')); ?>" alt=""> : ${task.priority}
                    </div>
                </div>
            `;
            taskList.insertAdjacentHTML('beforeend', newTask);

            // Reset and hide the form
            closeAddTaskModal();
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

// Drag-and-drop functionality
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

</script>

<!-- CSS -->
<style>
#project-details-page {
margin-left: 15px !important;
}
   #add-task-modal {
    display: none; /* Initially hidden */
    z-index: 1000;
    background-color: #fff;
    padding: 15px;
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
}
.hidden {
    display: none; /* Add hidden class behavior */
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
  .drive {
    display: flex;
    align-items: center;

  }
  .drive img {
    width: 15px;
    margin-right: 5px;
  }
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontends.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\Oct 29- Live edited-project management\resources\views/frontends/project-details-page.blade.php ENDPATH**/ ?>