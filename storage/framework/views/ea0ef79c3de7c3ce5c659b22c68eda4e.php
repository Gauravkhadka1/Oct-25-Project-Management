<?php $__env->startSection('main-container'); ?>

<?php
use Carbon\Carbon;

?>

<main>
    <div class="project-page">
        <div class="project-heading">
            <div class="project-h2">
                <h2>PROJECTS</h2>
            </div>
            <div class="create-filter-search-project">
                <div class="filter-section">
                    <div class="filter-payments" onclick="toggleFilterList()">
                    <img src="<?php echo e(url('public/frontend/images/new-bar.png')); ?>" alt="" class="barfilter">
                        <div class="filter-count">
                            <?php if($filterCount > 0): ?>
                            <p style="color: #b13a41;"><?php echo e($filterCount); ?></p>
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
                            <!-- <div class="filter-item">
                                <label for="status">Status:</label>
                                <select id="status" name="sort_status" class="filter-select">
                                    <option value="">Select Options</option>
                                    <option value="Design" <?php echo e(request('sort_status') == 'design' ? 'selected' : ''); ?>>Design</option>
                                    <option value="Development" <?php echo e(request('sort_status') == 'development' ? 'selected' : ''); ?>>Development</option>
                                    <option value="QA" <?php echo e(request('sort_status') == 'QA' ? 'selected' : ''); ?>>QA</option>
                                    <option value="Content Fillup" <?php echo e(request('sort_status') == 'content-fillup' ? 'selected' : ''); ?>>Content Fillup</option>
                                    <option value="Completed" <?php echo e(request('sort_status') == 'Completed' ? 'selected' : ''); ?>>Completed</option>
                          
                                    <option value="Other" <?php echo e(request('sort_status') == 'Other' ? 'selected' : ''); ?>>Other</option>

                                </select>
                            </div> -->

                            <button type="submit">Apply Filter</button>
                        </form>
                    </div>

                </div>
                <div class="search-payments">
                    <div class="search-icon">
                        <img src="public/frontend/images/search-light-color.png" alt="" class="searchi-icon">
                    </div>
                    <form action="<?php echo e(route('projects.index')); ?>" method="GET" id="search-form">
                        <div class="search-text-area">
                            <input type="text" name="search" placeholder="search..." value="<?php echo e(request('search')); ?>" oninput="this.form.submit()">
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="projects">
            <div class="ongoing-project" id="ongoing-project">
                <div class="task-board" id="project-task-board">
                    <!-- Column for To Do tasks -->
                    <div class="task-column" id="new-project" data-status="new">
                       <div class="new-project-add-heading">
                        <div class="new-project-heading">
                                <h3>NEW</h3>
                            </div>
                            <div class="new-projects-count" style="margin-right: -100px !important; font-weight:500;">
                                <?php echo e($newProjects); ?>

                            </div>
                        <div class="add-new-project">
                        <button class="btn-create-new" id="task-create" onclick="openAddTaskModal()">
                            <img src="<?php echo e(url('public/frontend/images/add-new.png')); ?>" alt="">
                        </button>
                        </div>
                       </div>
                       <div id="add-task-modal" class="hidden">
                            <form action="<?php echo e(route('projects.store')); ?>" method="POST" class="custom-form">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" id="project-id" name="project_id" value="">
                                <input type="hidden" id="status" name="status" value="new">

                                <div class="task-name">
                                    <input type="text" id="task-name" name="name" class="task-input" placeholder="Project name" required />
                                    <button type="submit" class="btn-save-task">Save</button>
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
                                        <input type="text" id="start-date" name="due_date" class="task-input" placeholder="Due Date" readonly required />
                                    </div>
                                    
                                </div>
                            </form>
                        </div>

                        <div class="task-list">
                            <?php $__currentLoopData = $projects->where('status', 'new'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="task" draggable="true" data-task-id="<?php echo e($project->id); ?>" data-task-type="<?php echo e(strtolower($project->category)); ?>">
                            <div class="task-name">
            <a href="javascript:void(0)" class="edit-project-name" data-project-id="<?php echo e($project->id); ?>">
                <p class="editable-name"><?php echo e($project->name); ?></p>
            </a>
        </div>
                                <!-- <div class="category">
                                    <strong>Status:</strong> <p><?php echo e($project->sub_status); ?></p>
                                </div> -->
                                <div class="project-start-date-view">
                                    <div class="project-start-date-view-img">
                                        <img src="<?php echo e(url('public/frontend/images/green-start-date.png')); ?>" alt="">:
                                    </div>
                               
                                    <span class="editable-start-date" data-project-id="<?php echo e($project->id); ?>"><?php echo e($project->start_date); ?></span>
                                </div>
                                <div class="project-due-date-view">
                                    <div class="project-due-date-view-img">
                                        <img src="<?php echo e(url('public/frontend/images/red-end-date.png')); ?>" alt="">:
                                    </div>
                                    <span class="editable-due-date" data-project-id="<?php echo e($project->id); ?>"><?php echo e($project->due_date); ?></span>
                                </div>
                                <div class="due-in-project-view">
                                    <div class="due-in-project-view-img">
                                        <img src="<?php echo e(url('public/frontend/images/due-date.png')); ?>" alt="">:
                                    </div>
                                    <div style="color: <?php echo e(Str::contains($project->time_left, 'Overdue') ? '#b13a41' : '#087641'); ?>">
                                        <?php echo e($project->time_left); ?>

                                    </div>
                                </div>

                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                    <div class="task-column" id="design" data-status="design">
                        <div class="heading-n-count">
                            <div class="design-heading-project">
                                <img src="<?php echo e(url ('public/frontend/images/design.png')); ?>" alt="">
                                <h3>DESIGN</h3>
                            </div>
                            <div class="projects-count">
                                <?php echo e($designProjects); ?>

                            </div>
                        </div>
                        <div class="task-list">
                            <?php $__currentLoopData = $projects->where('status', 'design'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="task" draggable="true" data-task-id="<?php echo e($project->id); ?>" data-task-type="<?php echo e(strtolower($project->category)); ?>">
                                <div class="task-name">
                                    <a href="<?php echo e(url ('projectdetails/' .$project->id)); ?>">
                                    <p style="font-size:15px;"><?php echo e($project->name); ?></p>
                                    </a>

                                </div>
                              <!-- <div class="category">
                                    <strong>Status:</strong> <p><?php echo e($project->sub_status); ?></p>
                                </div> -->
                                <div class="project-start-date-view">
                                    <div class="project-start-date-view-img">
                                        <img src="<?php echo e(url('public/frontend/images/green-start-date.png')); ?>" alt="">:
                                    </div>
                               
                                    <?php echo e($project->start_date); ?>

                                </div>
                                <div class="project-due-date-view">
                                    <div class="project-due-date-view-img">
                                        <img src="<?php echo e(url('public/frontend/images/red-end-date.png')); ?>" alt="">:
                                    </div>
                                    <?php echo e($project->due_date); ?>

                                </div>
                                <div class="due-in-project-view">
                                    <div class="due-in-project-view-img">
                                        <img src="<?php echo e(url('public/frontend/images/due-date.png')); ?>" alt="">:
                                    </div>
                                    <div style="color: <?php echo e(Str::contains($project->time_left, 'Overdue') ? '#b13a41' : '#087641'); ?>">
                                        <?php echo e($project->time_left); ?>

                                    </div>
                                </div>

                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                    <!-- Column for In Progress tasks -->
                    <div class="task-column" id="development" data-status="development">
                        <div class="heading-n-count">
                            <div class="developement-heading">
                            <img src="<?php echo e(url ('public/frontend/images/developement.png')); ?>" alt="">
                            <h3>DEVELOPMENT</h3>
                            </div>
                            <div class="projects-count">
                                <?php echo e($developmentProjects); ?>

                            </div>
                        </div>

                        <div class="task-list">
                            <?php $__currentLoopData = $projects->where('status', 'development'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="task" draggable="true" data-task-id="<?php echo e($project->id); ?>" data-task-type="<?php echo e(strtolower($project->category)); ?>">
                                <div class="task-name">
                                    <a href="<?php echo e(url ('projectdetails/' .$project->id)); ?>">
                                    <p style="font-size:15px;"><?php echo e($project->name); ?></p>
                                    </a>

                                </div>
                                <!-- <div class="category">
                                    <strong>Status:</strong> <p><?php echo e($project->sub_status); ?></p>
                                </div> -->
                                <div class="project-start-date-view">
                                    <div class="project-start-date-view-img">
                                        <img src="<?php echo e(url('public/frontend/images/green-start-date.png')); ?>" alt="">:
                                    </div>
                               
                                    <?php echo e($project->start_date); ?>

                                </div>
                                <div class="project-due-date-view">
                                    <div class="project-due-date-view-img">
                                        <img src="<?php echo e(url('public/frontend/images/red-end-date.png')); ?>" alt="">:
                                    </div>
                                    <?php echo e($project->due_date); ?>

                                </div>
                                <div class="due-in-project-view">
                                    <div class="due-in-project-view-img">
                                        <img src="<?php echo e(url('public/frontend/images/due-date.png')); ?>" alt="">:
                                    </div>
                                    <div style="color: <?php echo e(Str::contains($project->time_left, 'Overdue') ? '#b13a41' : '#087641'); ?>">
                                        <?php echo e($project->time_left); ?>

                                    </div>
                                </div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>

                    <!-- Column for QA tasks -->
                    <div class="task-column" id="quote_sent" data-status="content-fillup">
                       
                        <div class="heading-n-count">
                        <div class="content-fillup-heading">
                            <img src="<?php echo e(url ('public/frontend/images/content-fillup.png')); ?>" alt="">
                            <h3> CONTENT FILL UP</h3>
                        </div>
                            <div class="projects-count">
                                <?php echo e($contentfillupProjects); ?>

                            </div>
                        </div>
                        <div class="task-list">
                            <?php $__currentLoopData = $projects->where('status', 'content-fillup'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="task" draggable="true" data-task-id="<?php echo e($project->id); ?>" data-task-type="<?php echo e(strtolower($project->category)); ?>">
                                <div class="task-name">
                                    <a href="<?php echo e(url ('projectdetails/' .$project->id)); ?>">
                                    <p style="font-size:15px;"><?php echo e($project->name); ?></p>
                                    </a>

                                </div>
                                <!-- <div class="category">
                                    <strong>Status:</strong> <p><?php echo e($project->sub_status); ?></p>
                                </div> -->
                                <div class="project-start-date-view">
                                    <div class="project-start-date-view-img">
                                        <img src="<?php echo e(url('public/frontend/images/green-start-date.png')); ?>" alt="">:
                                    </div>
                               
                                    <?php echo e($project->start_date); ?>

                                </div>
                                <div class="project-due-date-view">
                                    <div class="project-due-date-view-img">
                                        <img src="<?php echo e(url('public/frontend/images/red-end-date.png')); ?>" alt="">:
                                    </div>
                                    <?php echo e($project->due_date); ?>

                                </div>
                                <div class="due-in-project-view">
                                    <div class="due-in-project-view-img">
                                        <img src="<?php echo e(url('public/frontend/images/due-date.png')); ?>" alt="">:
                                    </div>
                                    <div style="color: <?php echo e(Str::contains($project->time_left, 'Overdue') ? '#b13a41' : '#087641'); ?>">
                                        <?php echo e($project->time_left); ?>

                                    </div>
                                </div>

                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                    <div class="task-column" id="converted" data-status="completed">
                       
                        <div class="heading-n-count">
                        <div class="completed-heading-project">
                            <img src="<?php echo e(url ('public/frontend/images/completed.png')); ?>" alt="">
                            <h3>COMPLETED</h3>
                        </div>
                            <div class="projects-count">
                                <?php echo e($completedProjects); ?>

                            </div>
                        </div>
                        <div class="task-list">
                            <?php $__currentLoopData = $projects->where('status', 'completed'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="task" draggable="true" data-task-id="<?php echo e($project->id); ?>" data-task-type="<?php echo e(strtolower($project->category)); ?>">
                                <div class="task-name">
                                    <a href="<?php echo e(url ('projectdetails/' .$project->id)); ?>">
                                    <p style="font-size:15px;"><?php echo e($project->name); ?></p>
                                    </a>

                                </div>
                                 <!-- <div class="category">
                                    <strong>Status:</strong> <p><?php echo e($project->sub_status); ?></p>
                                </div> -->
                                <div class="project-start-date-view">
                                    <div class="project-start-date-view-img">
                                        <img src="<?php echo e(url('public/frontend/images/green-start-date.png')); ?>" alt="">:
                                    </div>
                               
                                    <?php echo e($project->start_date); ?>

                                </div>
                                <div class="project-due-date-view">
                                    <div class="project-due-date-view-img">
                                        <img src="<?php echo e(url('public/frontend/images/red-end-date.png')); ?>" alt="">:
                                    </div>
                                    <?php echo e($project->due_date); ?>

                                </div>
                                <!-- <div class="due-in-project-view">
                                    <div class="due-in-project-view-img">
                                        <img src="<?php echo e(url('public/frontend/images/due-date.png')); ?>" alt="">:
                                    </div>
                                    <div style="color: <?php echo e(Str::contains($project->time_left, 'Overdue') ? 'red' : 'green'); ?>">
                                        <?php echo e($project->time_left); ?>

                                    </div>
                                </div> -->

                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>

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
        

        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
        <script>

document.addEventListener("DOMContentLoaded", function() {
    flatpickr("#start-date", {
        dateFormat: "Y-m-d",  // Format of the date
        allowInput: true,      // Allow manual input
        onChange: function(selectedDates, dateStr, instance) {
            console.log("Selected date: " + dateStr);
        }
    });
});

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
        fetch("<?php echo e(route('projects.updateStatus')); ?>", {
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




            function openAddTaskModal() {
    document.getElementById('project-id'); // Set project ID
    console.log('Opening modal for adding project'); // Debug log

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
    if (!modal.contains(event.target) && !event.target.closest('.custom-form')) {
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
    fetch('', {
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
document.addEventListener('DOMContentLoaded', function() {
    // Function to handle inline editing
    function makeEditable(element, fieldType) {
        element.addEventListener('click', function() {
            const currentValue = element.textContent.trim();
            const input = document.createElement('input');
            input.type = fieldType === 'date' ? 'date' : 'text'; // Use 'date' type for date fields
            input.value = currentValue;
            element.innerHTML = ''; // Clear current content
            element.appendChild(input);

            // Focus the input field and select text for convenience
            input.focus();
            input.select();

            // When user presses Enter or clicks outside, save the change
            input.addEventListener('blur', () => saveChange(input, element, fieldType));
            input.addEventListener('keydown', function(event) {
                if (event.key === 'Enter') {
                    saveChange(input, element, fieldType);
                }
            });
        });
    }

    // Save the edited data to the server
    function saveChange(input, element, fieldType) {
        const newValue = input.value.trim();
        const projectId = element.getAttribute('data-project-id');

        if (newValue !== '') {
            // Make AJAX request to update the project data
            fetch(`/projects/${projectId}/update-inline`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    field: fieldType,
                    value: newValue
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update the display with the new value
                    element.innerHTML = newValue;
                } else {
                    // Handle error (optional)
                    alert('Error saving changes');
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        } else {
            element.innerHTML = input.value; // Revert if no value was entered
        }
    }

    // Make the name, start date, and due date editable
    document.querySelectorAll('.editable-name').forEach(element => makeEditable(element, 'text'));
    document.querySelectorAll('.editable-start-date').forEach(element => makeEditable(element, 'date'));
    document.querySelectorAll('.editable-due-date').forEach(element => makeEditable(element, 'date'));
});



        </script>
<style>
.placeholder {
    background: #fff;
    margin: 5px 0;
    border-radius: 5px;
}
</style>


</main>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('frontends.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\Oct 29- Live edited-project management\resources\views/frontends/projects.blade.php ENDPATH**/ ?>