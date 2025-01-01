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
                            <!--<div class="filter-item">-->
                            <!--    <label for="status">Status:</label>-->
                            <!--    <select id="status" name="sort_status" class="filter-select">-->
                            <!--        <option value="">Select Options</option>-->
                            <!--        <option value="Design" <?php echo e(request('sort_status') == 'design' ? 'selected' : ''); ?>>Design</option>-->
                            <!--        <option value="Development" <?php echo e(request('sort_status') == 'development' ? 'selected' : ''); ?>>Development</option>-->
                            <!--        <option value="QA" <?php echo e(request('sort_status') == 'QA' ? 'selected' : ''); ?>>QA</option>-->
                            <!--        <option value="Content Fillup" <?php echo e(request('sort_status') == 'content-fillup' ? 'selected' : ''); ?>>Content Fillup</option>-->
                            <!--        <option value="Completed" <?php echo e(request('sort_status') == 'Completed' ? 'selected' : ''); ?>>Completed</option>-->
                          
                            <!--        <option value="Other" <?php echo e(request('sort_status') == 'Other' ? 'selected' : ''); ?>>Other</option>-->

                            <!--    </select>-->
                            <!--</div>-->

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
                        <?php if($projects->where('status', 'new')->isEmpty()): ?>
                                <p>No NEW Projects</p>
                            <?php else: ?>
                            <?php $__currentLoopData = $projects->where('status', 'new'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="task" draggable="true" data-task-id="<?php echo e($project->id); ?>" data-task-type="<?php echo e(strtolower($project->category)); ?>">
                                <div class="task-name">
                                    <a href="<?php echo e(url ('projectdetails/' .$project->id)); ?>">
                                    <p style="font-size:15px;"><?php echo e($project->name); ?></p>
                                    </a>
                                    <a href="https://docs.google.com/document/d/1RHgW0r-pmV3kd-iZsFTczF4W1HToQ7WdUyGh59GrD4A/edit?tab=t.0">
                                    <img src="<?php echo e(url('public/frontend/images/info.png')); ?>" alt="">
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
                            <?php endif; ?>
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
                        <?php if($projects->where('status', 'design')->isEmpty()): ?>
                                <p>No Projects in Design</p>
                            <?php else: ?>
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
                            <?php endif; ?>
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
                        <?php if($projects->where('status', 'development')->isEmpty()): ?>
                                <p>No Projects in Development</p>
                            <?php else: ?>
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
                            <?php endif; ?>
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
                        <?php if($projects->where('status', 'content-fillup')->isEmpty()): ?>
                                <p>No Projects in Content-fillup</p>
                            <?php else: ?>
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
                            <?php endif; ?>
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
                        <?php if($projects->where('status', 'completed')->isEmpty()): ?>
                                <p>No Projects in Completed</p>
                            <?php else: ?>
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
                                <!--<div class="due-in-project-view">-->
                                <!--    <div class="due-in-project-view-img">-->
                                <!--        <img src="<?php echo e(url('public/frontend/images/due-date.png')); ?>" alt="">:-->
                                <!--    </div>-->
                                <!--    <div style="color: <?php echo e(Str::contains($project->time_left, 'Overdue') ? 'red' : 'green'); ?>">-->
                                <!--        <?php echo e($project->time_left); ?>-->
                                <!--    </div>-->
                                <!--</div>-->

                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
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



        </script>

<style>
    .project-page {
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
   /* background-color: red; */
  }
  .project-heading {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 30px;
    width: 100%;
    /* background-color: red; */
    margin-top: -5px;
  }
  
  .project-heading h2{
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 20px 10px;
    margin-top: 10px;
    font-size: 35px;
    font-weight: 500;
  }
  
  .project-tags span{
    border: 2px solid gray;
    border-radius: 10px;
    padding: 5px;
    margin-right: 10px;
  }

    /* Modal Background */
    #create-project-modal {
      display: none; /* Hidden by default */
      position: fixed; /* Stay in place */
      z-index: 1000; /* Sit on top */
      left: 0;
      top: 0;
      width: 100%; /* Full width */
      height: 100%; /* Full height */
      background-color: rgba(0, 0, 0, 0.5); /* Black background with transparency */
      display: flex;
      justify-content: center;
      align-items: center;
  }

  /* Modal Content */
  #create-project-modal > div {
      background-color: #fff;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      width: 100%;
      max-width: 600px;
      position: relative;
  }

  /* Modal Heading */
  #create-project-modal h3 {
      margin-bottom: 20px;
      font-size: 24px;
      color: #333;
      text-align: center;
  }

  /* Form Styling */
  #create-project-modal form {
      display: flex;
      flex-direction: column;
      gap: 15px;
  }

  /* Input Styling */
  #create-project-modal label {
      font-weight: bold;
      color: #555;
      margin-bottom: 5px;
  }

  #create-project-modal input[type="text"],
  #create-project-modal input[type="date"],
  #create-project-modal select {
      padding: 10px;
      width: 100%;
      border: 1px solid #ccc;
      border-radius: 5px;
      font-size: 16px;
  }

  /* Button Styling */
  #create-project-modal button {
      padding: 5px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-size: 16px;
      transition: background-color 0.3s;
    
  }

  #create-project-modal button[type="submit"] {
      background-color: #28a745; /* Green for Submit */
      color: white;
  }

  #create-project-modal button[type="submit"]:hover {
      background-color: #218838;
  }

  #create-project-modal button[type="button"] {
      background-color: #dc3545; /* Red for Cancel */
      color: white;
  }

  #create-project-modal button[type="button"]:hover {
      background-color: #c82333;
  }

  /* Responsive Modal */
  @media (max-width: 768px) {
      #create-project-modal > div {
          width: 90%; /* Adjust the width for smaller screens */
      }
  }

  .activity-card {
    border: 1px solid #ccc; /* Border around each card */
    border-radius: 5px; /* Rounded corners */
    padding: 10px; /* Padding inside the card */
    margin: 10px 0; /* Margin between cards */
    background-color: #f9f9f9; /* Light background */
    box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.1); /* Shadow for depth */
}

.activity-card p {
    margin: 5px 0; /* Spacing between paragraphs */
}

#add-task-modal {
    margin-top: 10px;
}

.styled-table-project {
    width: 100%;
    border-collapse: collapse;
    margin: 25px 0;
    font-size: 15px;
    font-family: 'Arial', sans-serif;
    text-align: left;
}

.styled-table-project thead tr {
    background-color: #92A33F;
    color: white;
    font-weight: bold;
}

.styled-table-project th, .styled-table-project td {
    padding: 12px 15px;
}

.styled-table-project tbody tr {
    border-bottom: 1px solid #dddddd;
}

.styled-table-project tbody tr:nth-of-type(even) {
    background-color: #f3f3f3;
}

.styled-table-project tbody tr:last-of-type {
    border-bottom: 2px solid #009879;
}

.styled-table-project tbody tr:hover {
    background-color: #f1f1f1;
    cursor: pointer;
}

.styled-table-project select {
    padding: 5px;
    font-size: 16px;
    border: 1px solid #ccc;
    border-radius: 4px;
}

.create-project button {
    background-color: #4CAF50;
    padding: 8px 13px;
    border: none;
    border-radius: 5px;
    color: white;
    font-size: 15px;
    margin-bottom: 0px;
}
.close {
    font-size: 35px;
    color: rgb(145, 12, 12);
}

.btn-edit {
    background-color: rgb(216, 52, 52);
    padding: 5px 10px;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s;
    font-size: 15px;
}

/* Custom Modal Background */
.custom-modal {
    display: flex; /* Center content */
    justify-content: center;
    align-items: center;
    position: fixed;
    z-index: 1000; /* Modal on top */
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.6); /* Dark overlay */
}

/* Custom Modal Content */
.custom-modal-content {
    background-color: #fff; /* White background */
    padding: 20px;
    border-radius: 10px; /* Rounded corners */
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3); /* Soft shadow */
    width: 400px; /* Fixed width */
    max-width: 90%; /* Responsive */
    margin-top: 70px;
    padding-right: 24px;
}

/* Custom Close Button */
.custom-close {
    color: #888; /* Grey color */
    float: right; /* Top right corner */
    font-size: 30px;
    font-weight: bold;
    cursor: pointer; /* Pointer on hover */
}

/* Close Button Hover Effect */
.custom-close:hover,
.custom-close:focus {
    color: #000; /* Darker on hover */
}

/* Custom Form Elements */
.custom-label {
    display: block; /* Block layout */
    margin: 10px 0 5px; /* Spacing */
    font-weight: bold; /* Bold text */
}

.custom-input,
.custom-select {
    width: 100%; /* Full width */
    padding: 10px; /* Padding */
    border: 1px solid #ccc; /* Light border */
    border-radius: 5px; /* Rounded corners */
    margin-bottom: 15px; /* Space between inputs */
}

/* Custom Button Styles */
.custom-submit-button {
    background-color: #007BFF; /* Blue background */
    color: white; /* White text */
    padding: 10px 15px; /* Padding */
    border: none; /* No border */
    border-radius: 5px; /* Rounded corners */
    cursor: pointer; /* Pointer on hover */
    transition: background-color 0.3s; /* Smooth transition */
}

.custom-submit-button:hover {
    background-color: #0056b3; /* Darker blue on hover */
}

.custom-cancel-button {
    background-color: #dc3545; /* Red background */
    color: white; /* White text */
    padding: 10px 15px; /* Padding */
    border: none; /* No border */
    border-radius: 5px; /* Rounded corners */
    cursor: pointer; /* Pointer on hover */
}

.custom-cancel-button:hover {
    background-color: #c82333; /* Darker red on hover */
}

.barfilter {
    width: 14px !important;
    margin-left: 5px;
    /* padding: 5px; */
}

.btn-see-details {
    background-color: transparent;
    width: 30px;
    border: none;
}
.btn-see-details img {
    width: 30px;
}
.create-project button {
    margin-bottom: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 2px 10px;
    border-radius: 10px;
    background-color: transparent;
}
.create-project button:hover {
    background-color: rgb(216, 212, 212);
}
.create-project img {
    width: 23px;
    margin-right: 5px;
}
.btn-edit {
    background-color: transparent;
}
.btn-edit:hover {
    background-color: #3CBFA1;
}
.btn-edit img {
    width: 30px;
}
.btn-create {
    color: black;
}
#task-create {
    color: black !important;
}

.projects-name-buttons {
    display: flex;
    align-items: center;
    justify-content: left;
}
.btn-view-activities-p {
    display: flex;
    align-items: center;
    justify-content: center;
    color: black !important;
    border: none;
    background-color: transparent;
    margin: 5px 0 0 0;
    padding: 8px 12px;
    border-radius: 5px;
}
.btn-view-activities-p:hover {
    background-color: #36BFA1;
}
.btn-view-activities-p img {
    margin-right: 5px;
    width: 25px;
}

.modal-content {
    max-width: 100%;
    /* background-color: red; */
}
.create-filter-search-project {
    display: flex;
    align-items: center;
    justify-content: left;
}
.search-projects {
    display: flex;
    border: 2px solid gray;
    border-radius: 10px;
    padding: 5px;
    margin-left: 10px;
    font-size: 10px;
}
.search-icon img {
    width: 18px !important;
}
.search-projects form {
    display: flex;
}
.search-text-area input {
    border: none;
    font-size: 15px !important;
    padding-left: 10px;
}
.search-text-area input:focus {
    border: none; 
    outline: none; 
}
.filter-projects {
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid gray;
    border-radius: 10px;
    padding: 3px 6px;
    margin-left: 15px;
}
.filter-projects img {
    margin-right: 2px;
    width: 2px;
}
.filter-projects p {
    font-size: 14px;
}
.search-projects {
    display: flex;
    border: 1px solid gray;
    border-radius: 10px;
    padding: 5px 8px;
    margin-left: 20px;
    width: 105px;
}
.search-projects img {
    width: 15px !important;
    margin-right: 5px;
}
.search-projects form {
    display: flex;
}
.search-text-area input {
    border: none;
    font-size: 14px;
    padding-left: 5px !important;
    /* background-color: red; */
    width: 95%;
}
.search-text-area input:focus {
    
    border: none; 
    outline: none; 
}

/* project detail page  */
.project-name-status {
    display: flex;
    align-items: center;
    justify-content: flex-start;
}
.sub-status {
    display: flex;
    align-items: center;
    justify-content: flex-start;
    margin-left: 20px;
}
.todo-add {
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.btn-create-new {
    background-color: transparent;
    padding: 0;
}
.btn-create-new:hover {
    background-color: transparent;
}
.btn-create-new img {
    width: 25px;
}
.hidden {
    display: none;
}
.project-n {
padding: 10px 15px;
}

.project-n p {
font-size: 20px;
font-weight: 520;
}
.projects {
    /* background-color: yellow; */
    width: 100%;
    padding: 0 20px;
}
.new-project-add-heading {
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.new-project-heading {
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #1090E0;
    width: 50px; 
    padding: 4px 8px 4px 4px;
    border-radius: 7px;
   
}

.new-project-heading h3 {
    font-size: 12px !important;
}
.new-project-heading img {
    width: 10px;
    margin-right: 7px;
}
.add-new-project img {
    width: 15px;
    margin-right: 10px;
}
.design-heading-project {
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #e16b16;
    width: 85px; 
    padding: 4px 8px 4px 4px;
    border-radius: 7px;
}
.design-heading-project  h3 {
    font-size: 12px !important;
}
.design-heading-project img {
    width: 15px;
    margin-right: 7px;
}
.developement-heading {
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #f7b04f;
    width: 124px; 
    padding: 4px 8px 4px 4px;
    border-radius: 7px;
}
.developement-heading h3 {
    font-size: 12px !important;
}
.developement-heading img {
    width: 15px;
    margin-right: 10px;
}
.content-fillup-heading {
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #4f72f3;
    width: 145px; 
    padding: 4px 8px 4px 4px;
    border-radius: 7px;
}
.content-fillup-heading h3 {
    font-size: 12px !important;
}
.content-fillup-heading img {
    width: 15px;
    margin-right: 10px;
}
.completed-heading-project {
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #318945;
    width: 105px; 
    padding: 4px 8px 4px 4px;
    border-radius: 7px;
}
.completed-heading-project h3 {
    font-size: 12px !important;
}
.completed-heading-project img {
    width: 15px;
    margin-right: 10px;
}
#assigned-pic {
    margin-left: 5px;
}
#new-project.task-column {
    background-color: #F5F9FD;
}
#design.task-column {
    background-color: #fdf7f3;
}
#development.task-column {
    background-color: #fff1dd;
}
#quote_sent.task-column {
    background-color: #f7f5fe;
}
#aggrement_sent.task-column {
    background-color: #fdf4f4;
}
#converted.task-column {
    background-color: #f3f9f5;
}

.project-start-date {
    display: flex;
    align-items: flex-start;
    justify-content: center;
    flex-direction: column;
    margin-top: 10px;
    margin-left: 0px;
}
.project-start-date:hover {
    background-color: #F0F1F3;
}
.project-start-date p {
    margin-left: 5px;
    font-weight: 400;
}
.project-start-date img {
    width: 15px;
    margin-right: 10px;
}
.start-date-input {
    display: flex;
    align-items: center;
    justify-content: center;
}
.project-due-date {
    display: flex;
    align-items: flex-start;
    justify-content: center;
    flex-direction: column;
    margin-top: 10px;
    margin-left: 0px;
}
.project-due-date:hover {
    background-color: #F0F1F3;
}
.project-due-date p {
    margin-left: 5px;
    font-weight: 500;
}
.project-due-date img {
    width: 15px;
    margin-right: 10px;
}
.due-date-input {
    display: flex;
    align-items: center;
    justify-content: center;
}
.project-start-date-view {
    display: flex;
    align-items: center;
    justify-content: flex-start;
    font-size: 14px;
    margin-top: 5px;
    color: #6b6d70;
    font-weight: 500;
}
.project-start-date-view:hover {
    background-color: #F0F1F3;
}
.project-start-date-view-img{
    display: flex;
    align-items: center;
    justify-content: flex-start;
    margin-right: 8px;
}
.project-start-date-view-img img{
    width: 15px;
    margin-right: 2px;
}

.project-start-date-view p {
    margin-right: 5px;
    font-weight: 500;
}
.project-due-date-view {
    display: flex;
    align-items: center;
    justify-content: flex-start;
    font-size: 14px;
    margin-top: 5px;
    color: #6b6d70;
    font-weight: 500;
}
.project-due-date-view:hover {
    background-color: #F0F1F3;
}
.project-due-date-view-img{
    display: flex;
    align-items: center;
    justify-content: flex-start;
    margin-right: 8px;
}
.project-due-date-view-img img{
    width: 15px;
    margin-right: 2px;
}

.project-due-date-view p {
    margin-right: 5px;
    font-weight: 500;
}
.due-in-project-view {
    display: flex;
    align-items: center;
    justify-content: flex-start;
    font-size: 14px;
    margin-top: 5px;
    font-weight: 500;
}
.due-in-project-view:hover {
    background-color: #F0F1F3;
}
.due-in-project-view-img{
    display: flex;
    align-items: center;
    justify-content: flex-start;
    margin-right: 8px;
}
.due-in-project-view img{
    width: 15px;
    margin-right: 2px;
}
.due-in-project-view p {
    margin-right: 5px;
}
.project-h2 h2{
    font-size: 20px;
    margin-left: -8px;
    color: #2A2E34;
}
.heading-n-count {
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.projects-count {
    margin-right: 15px;
    font-weight: 500;
}
#project-task-board {
    height: 82vh;
}
</style>

</main>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('frontends.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\Oct 29- Live edited-project management\resources\views/frontends/projects.blade.php ENDPATH**/ ?>