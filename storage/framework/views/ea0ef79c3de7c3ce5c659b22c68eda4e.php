<?php $__env->startSection('main-container'); ?>

<?php
use Carbon\Carbon;
?>

<main>
    <div class="project-page">
        <div class="project-heading">
            <h2>Projects</h2>
        </div>
        <div class="projects">
            <div class="ongoing-project" id="ongoing-project">
                <div class="create-filter-search-project">
                    <div class="create-project">
                        <button onclick="openCreateProjectModal()"><img src="<?php echo e(url ('/frontend/images/plus.png')); ?>" alt=""> Project</button>
                    </div>
                    <div class="filter-section">
                        <div class="filter-projects" onclick="toggleFilterList()">
                            <img src="frontend/images/bars-filter.png" alt="" class="barfilter">
                            <div class="filter-count">
                                <?php if($filterCount > 0): ?>
                                <p><?php echo e($filterCount); ?></p>
                                <?php endif; ?>
                            </div>
                            Filter
                        </div>
                        <div class="filter-options" style="display: none;">
                            <form action="<?php echo e(route('projects.index')); ?>" method="GET">
                                <!-- Inquiry Date Filter -->
                                <div class="filter-item">
                                    <label for="start-date">Start Date:</label>
                                    <select id="start-date" name="start_date" class="filter-select" onchange="handleDateRange(this)">
                                        <option value="">Select Options</option>
                                        <option value="recent" <?php echo e(request('start_date') == 'recent' ? 'selected' : ''); ?>>Recent</option>
                                        <option value="oldest" <?php echo e(request('start_date') == 'oldest' ? 'selected' : ''); ?>>Oldest</option>
                                        <option value="date-range" <?php echo e(request('start_date') == 'date-range' ? 'selected' : ''); ?>>Choose Date Range</option>
                                    </select>
                                    <div id="date-range-picker" class="date-range-picker" style="display: <?php echo e(request('inquiry-date') == 'date-range' ? 'block' : 'none'); ?>">
                                        <label for="from-date">From:</label>
                                        <input type="date" id="from-date" name="from_date" value="<?php echo e(request('from_date')); ?>">
                                        <label for="to-date">To:</label>
                                        <input type="date" id="to-date" name="to_date" value="<?php echo e(request('to_date')); ?>">
                                    </div>
                                </div>

                                <div class="filter-item">
                                    <label for="due-date">Due Date:</label>
                                    <select id="due-date" name="due_date" class="filter-select" onchange="handleDateRange(this)">
                                        <option value="">Select Options</option>
                                        <option value="Less-Time" <?php echo e(request('due_date') == 'Less-Time' ? 'selected' : ''); ?>>Less Time</option>
                                        <option value="More-Time" <?php echo e(request('due_date') == 'More-Time' ? 'selected' : ''); ?>>More Time</option>
                                        <option value="date-range" <?php echo e(request('due_date') == 'date-range' ? 'selected' : ''); ?>>Choose Date Range</option>
                                    </select>
                                    <div id="date-range-picker" class="date-range-picker" style="display: <?php echo e(request('inquiry-date') == 'date-range' ? 'block' : 'none'); ?>">
                                        <label for="from-date">From:</label>
                                        <input type="date" id="from-date" name="from_date" value="<?php echo e(request('from_date')); ?>">
                                        <label for="to-date">To:</label>
                                        <input type="date" id="to-date" name="to_date" value="<?php echo e(request('to_date')); ?>">
                                    </div>
                                </div>

                                <!-- Status Filter -->
                                <div class="filter-item">
                                    <label for="status">Status:</label>
                                    <select id="status" name="sort_status" class="filter-select">
                                        <option value="">Select Options</option>
                                        <option value="Design" <?php echo e(request('sort_status') == 'Design' ? 'selected' : ''); ?>>Design</option>
                                        <option value="Development" <?php echo e(request('sort_status') == 'Development' ? 'selected' : ''); ?>>Development</option>
                                        <option value="QA" <?php echo e(request('sort_status') == 'QA' ? 'selected' : ''); ?>>QA</option>
                                        <option value="Content Fillup" <?php echo e(request('sort_status') == 'Content Fillup' ? 'selected' : ''); ?>>Content Fillup</option>
                                        <option value="Completed" <?php echo e(request('sort_status') == 'Completed' ? 'selected' : ''); ?>>Completed</option>
                                        <option value="Closed" <?php echo e(request('sort_status') == 'Closed' ? 'selected' : ''); ?>>Closed</option>
                                        <option value="Other" <?php echo e(request('sort_status') == 'Other' ? 'selected' : ''); ?>>Other</option>

                                    </select>
                                </div>

                                <button type="submit">Apply Filter</button>
                            </form>
                        </div>

                    </div>
                    <div class="search-projects">
                        <div class="search-icon">
                            <img src="frontend/images/search-icon.png" alt="" class="searchi-icon">
                        </div>
                        <form action="<?php echo e(route('projects.index')); ?>" method="GET" id="search-form">
                            <div class="search-text-area">
                                <input type="text" name="search" placeholder="search projects" value="<?php echo e(request('search')); ?>" oninput="this.form.submit()">
                            </div>
                        </form>
                    </div>

                </div>
                <table class="modern-payments-table">
                    <thead>
                        <tr>
                            <th>SN</th>
                            <th>
                                Projects
                            </th>
                            <th>
                                Start Date
                            </th>
                            <th>
                                Due Date
                            </th>
                            <th>
                                Status
                            <th>
                                Time Left
                            </th>
                            <th>Edit</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($key + 1); ?></td>
                            <td><?php echo e($project->name); ?>

                                <div class="projects-name-buttons">
                                    <button class="btn-create" id="task-create" onclick="openAddTaskModal(<?php echo e($project->id); ?>)"><img src="<?php echo e(url ('/frontend/images/plus.png')); ?>" alt=""> Task</button>
                                    <button class="btn-view-activities-p" onclick="openTaskDetailsModal(<?php echo e(json_encode($project)); ?>)"><img src="<?php echo e(url ('/frontend/images/view.png')); ?>" alt="">Tasks</button>
                                </div>
                            </td>


                            <td><?php echo e($project->start_date); ?></td>
                            <td><?php echo e($project->due_date); ?></td>
                            <td><?php echo e($project->status); ?></td>
                            <td>
                                <?php if(is_null($project->start_date) || is_null($project->due_date)): ?>
                                N/A
                                <?php else: ?>
                                <?php
                                $startDate = \Carbon\Carbon::parse($project->start_date);
                                $dueDate = \Carbon\Carbon::parse($project->due_date);
                                $currentDate = \Carbon\Carbon::now();
                                $daysLeft = $currentDate->startOfDay()->diffInDays($dueDate, false);
                                ?>

                                <?php if($daysLeft > 0): ?>
                                <?php echo e($daysLeft); ?> days left
                                <?php elseif($daysLeft === 0): ?>
                                Due today
                                <?php else: ?>
                                Overdue by <?php echo e(abs($daysLeft)); ?> days
                                <?php endif; ?>
                                <?php endif; ?>
                            </td>
                            <!-- Add a button to add tasks for each project -->
                            <td>

                                <button class="btn-edit" onclick="openEditProjectModal(<?php echo e(json_encode($project)); ?>)"><img src="<?php echo e(url ('/frontend/images/edit.png')); ?>" alt=""></button>
                                <!-- <form action="<?php echo e(route('project.destroy', $project->id)); ?>" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this project?');">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn-cancel">Delete</button>
                                    </form> -->
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal for Creating New Project -->
        <div id="create-project-modal" style="display: none;">
            <div>
                <h3>Create New Project</h3>
                <form action="<?php echo e(route('projects.store')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <label for="project-name">Project Name:</label>
                    <input type="text" name="name" id="project-name">

                    <label for="start-date">Start Date:</label>
                    <input type="date" name="start_date" id="start_date">

                    <label for="due-date">Due Date:</label>
                    <input type="date" name="due_date" id="due_date">

                    <label for="assignee">Status</label>
                    <select name="status" id="status">
                        <option value="Design">Design</option>
                        <option value="Development">Development</option>
                        <option value="QA">QA</option>
                        <option value="Content fillup">Content fillup</option>
                        <option value="Content fillup">Other</option>
                    </select>

                    <button type="submit">Add Project</button>
                    <button type="button" onclick="closeCreateProjectModal()">Cancel</button>
                </form>
            </div>
        </div>

        <!-- Modal for Editing Project -->

        <div id="edit-project-modal" class="modal" style="display: none;">
            <div class="modal-content">
                <h3>Edit Prospect</h3>
                <form id="edit-project-form" action="<?php echo e(route('projects.update', '')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    <label for="edit-project-name">Project Name:</label>
                    <input type="text" name="name" id="edit-project-name"><br>

                    <label for="edit-start-date">Start Date:</label>
                    <input type="date" name="start_date" id="edit-start_date">

                    <label for="edit-due-date">Due Date:</label>
                    <input type="date" name="due_date" id="edit-due_date">

                    <label for="assignee">Status</label>
                    <select name="status" id="edit-status">
                        <option value="Design">Design</option>
                        <option value="Development">Development</option>
                        <option value="QA">QA</option>
                        <option value="Content fillup">Content fillup</option>
                        <option value="Content fillup">Other</option>
                    </select>

                    <div class="modal-buttons">
                        <button type="submit" class="btn-submit">Update Prospect</button>
                        <button type="button" class="btn-cancel" onclick="closeEditProjectModal()">Cancel</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal for Task Details -->
        <div id="task-details-modal" class="modal" style="display: none;">
            <div class="modal-content">
                <span class="close" onclick="closeTaskDetailsModal()">&times;</span>
                <h3 id="project-name-modal">Project Tasks</h3>

                <!-- Add Task Button -->


                <table class="styled-table-project">
                    <thead>
                        <tr>
                            <th>Task Name</th>
                            <th>Assigned To</th>
                            <th>Assigned By</th>
                            <th>Start Date</th>
                            <th>Due Date</th>
                            <th>Priority</th>
                            <th>Time Taken</th>
                        </tr>
                    </thead>
                    <tbody id="task-details-body">
                        <!-- Task details will be populated here dynamically -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal for Adding New Task -->
        <div id="add-task-modal" class="custom-modal" style="display: none;">
            <div class="custom-modal-content">
                <span class="custom-close" onclick="closeAddTaskModal()">&times;</span>
                <h3 class="custom-modal-title">Add New Task</h3>
                <form action="<?php echo e(route('tasks.store')); ?>" method="POST" class="custom-form">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" id="project-id" name="project_id" value="">

                    <label for="task-name" class="custom-label">Task Name:</label>
                    <input type="text" name="name" id="task-name" class="custom-input" required>

                    <label for="assigned-to" class="custom-label">Assigned To:</label>
                    <select name="assigned_to" id="assigned-to" class="custom-select" required>
                        <option value="">Select User</option>
                        <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($user->email); ?>"><?php echo e($user->username); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>

                    <label for="start-date" class="custom-label">Start Date:</label>
                    <input type="date" name="start_date" id="start-date" class="custom-input">

                    <label for="due-date" class="custom-label">Due Date:</label>
                    <input type="date" name="due_date" id="due-date" class="custom-input">

                    <label for="priority" class="custom-label">Priority:</label>
                    <select name="priority" id="priority" class="custom-select">
                        <option value="Normal">Normal</option>
                        <option value="High">High</option>
                        <option value="Urgent">Urgent</option>
                    </select>

                    <button type="submit" class="custom-submit-button">Add Task</button>
                    <button type="button" class="custom-cancel-button" onclick="closeAddTaskModal()">Cancel</button>
                </form>
            </div>
        </div>

        <script>
            function openCreateProjectModal() {
                document.getElementById('create-project-modal').style.display = 'block';
            }

            function closeCreateProjectModal() {
                document.getElementById('create-project-modal').style.display = 'none';
            }

            function openEditProjectModal(project) {
                document.getElementById('edit-project-name').value = project.name;
                document.getElementById('edit-start_date').value = project.start_date; // Fixed the ID
                document.getElementById('edit-due_date').value = project.due_date; // Fixed the ID
                document.getElementById('edit-status').value = project.status;

                // Set the form action to include the project ID
                const form = document.getElementById('edit-project-form');
                form.action = "<?php echo e(route('projects.update', '')); ?>/" + project.id;

                document.getElementById('edit-project-modal').style.display = 'block';
            }


            function closeEditProjectModal() {
                document.getElementById('edit-project-modal').style.display = 'none';
            }

            function openAddTaskModal(projectId) {
                // Set the hidden input for project ID
                document.getElementById('project-id').value = projectId;
                console.log('Opening modal for project ID:', projectId);
                document.getElementById('add-task-modal').style.display = 'block';
            }
            var project = <?php echo json_encode($project, 15, 512) ?>; // Pass project data as JSON
            let timers = {};

            function openTaskDetailsModal(project) {
                console.log(project); // Log the entire project object

                // Set project name in modal heading
                document.getElementById('project-name-modal').innerText = project.name + " - Task Details";

                // Get the task details (assumed to be loaded as part of the project object)
                let tasks = project.tasks || []; // Ensure tasks is an array
                console.log(tasks); // Check tasks array

                let taskDetailsBody = document.getElementById('task-details-body');
                taskDetailsBody.innerHTML = ''; // Clear previous content

                // Loop through tasks and create table rows
                tasks.forEach(task => {
                    // Check if task has necessary properties
                    if (task) {
                        let assignedToUsername = task.assigned_user ? task.assigned_user.username : 'N/A'; // Use the username instead of ID
                        let assignedByUsername = task.assigned_by ? task.assigned_by.username : 'N/A'; // If you want to display assigned by username as well

                        // Initialize timer for the task
                        if (!timers[task.id]) {
                            timers[task.id] = {
                                elapsedTime: task.elapsed_time * 1000 || 0, // Convert to milliseconds
                                running: false
                            };
                        }

                        // Create the row with a timer display
                        let row = `<tr>
                    <td>${task.name || 'N/A'}</td>
                    <td>${assignedToUsername}</td>
                    <td>${assignedByUsername}</td>
                    <td>${task.start_date ? new Date(task.start_date).toISOString().split('T')[0] : 'N/A'}</td>
                    <td>${task.due_date ? new Date(task.due_date).toISOString().split('T')[0] : 'N/A'}</td>
                    <td>${task.priority || 'N/A'}</td>
                    <td id="time-${task.id}">${formatTime(timers[task.id].elapsedTime)}</td>
                </tr>`;
                        taskDetailsBody.innerHTML += row;

                        // Update the timer display for each task
                        updateTimerDisplay(task.id);
                    }
                });
                document.getElementById('task-details-modal').style.display = 'block';

            }

            function formatTime(milliseconds) {
                const totalSeconds = Math.floor(milliseconds / 1000);
                const hours = Math.floor(totalSeconds / 3600);
                const minutes = Math.floor((totalSeconds % 3600) / 60);
                const seconds = totalSeconds % 60;
                return `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            }

            function updateTimerDisplay(taskId) {
                const timer = timers[taskId];
                if (timer) {
                    const totalSeconds = Math.floor(timer.elapsedTime / 1000);
                    document.getElementById(`time-${taskId}`).innerText = formatTime(timer.elapsedTime);
                }
            }

            function closeTaskDetailsModal() {
                document.getElementById('task-details-modal').style.display = 'none';
            }

            function closeAddTaskModal() {
                document.getElementById('add-task-modal').style.display = 'none';
            }

            function toggleFilterList() {
                const filterOptions = document.querySelector('.filter-options');
                filterOptions.style.display = filterOptions.style.display === 'none' ? 'block' : 'none';

                // Populate the select options with the current selected values only if the options are shown
                if (filterOptions.style.display === 'block') {
                    populateSelectedFilters();
                }
            }

            function populateSelectedFilters() {
                const startDateSelect = document.getElementById('start-date');
                const dueDateSelect = document.getElementById('due-date');
                const statusSelect = document.getElementById('status');

                // Get the selected values from the current state
                const selectedStartDate = startDateSelect.value;
                const selectedDueDate = dueDateSelect.value;
                const selectedStatus = statusSelect.value;

                // Update the display of the inquiry date select to show the selected value
                if (selectedInquiryDate === 'date-range') {
                    document.getElementById('date-range-picker').style.display = 'block';
                } else {
                    document.getElementById('date-range-picker').style.display = 'none';
                }

                startDateSelect.value = selectedStartDate;
                dueDateSelect.value = selectedDueDate;
                statusSelect.value = selectedStatus;
            }

            // Optional: Close the filter options if clicking outside of them
            document.addEventListener('click', function(event) {
                const filterDiv = document.querySelector('.filter-projects');
                const filterOptions = document.querySelector('.filter-options');
                if (!filterDiv.contains(event.target) && !filterOptions.contains(event.target)) {
                    filterOptions.style.display = 'none';
                    document.getElementById('date-range-picker').style.display = 'none';
                }
            });

            function handleDateRange(selectElement) {
                const dateRangePicker = document.getElementById('date-range-picker');
                dateRangePicker.style.display = selectElement.value === 'date-range' ? 'flex' : 'none';
            }

            function applyFilter() {
                const startDate = document.getElementById('start-date').value;
                const dueDate = document.getElementById('due-date').value;
                const status = document.getElementById('status').value;
                const fromDate = document.getElementById('from-date').value;
                const toDate = document.getElementById('to-date').value;

                const url = new URL(window.location.href);
                url.searchParams.set('start_date', startDate);
                url.searchParams.set('due_date', dueDate);
                url.searchParams.set('sort_status', status);

                if (startDate === 'date-range') {
                    url.searchParams.set('from_date', fromDate);
                    url.searchParams.set('to_date', toDate);
                }
                if (dueDate === 'date-range') {
                    url.searchParams.set('from_date', fromDate);
                    url.searchParams.set('to_date', toDate);
                }

                window.location.href = url.toString();
            }
        </script>


</main>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('frontends.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\Oct 29- Live edited-project management\resources\views/frontends/projects.blade.php ENDPATH**/ ?>