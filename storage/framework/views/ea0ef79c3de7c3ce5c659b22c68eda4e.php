

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
                <table class="styled-table">
                    <thead>
                        <tr>
                            <th>SN</th>   
                            <th>
                                Projects
                                <a href="#" onclick="toggleFilter('project-name-sort')">
                                    <img src="frontend/images/bars-filter.png" alt="" class="barfilter">
                                </a>
                                <div id="project-name-sort" class="filter-dropdown" style="display: none;">
                                    <form action="<?php echo e(route('projects.index')); ?>" method="GET">
                                        <select name="sort_project_name" onchange="this.form.submit()">
                                            <option value="">Sort by Project Name</option>
                                            <option value="a_to_z" <?php echo e(request('sort_project_name') == 'a_to_z' ? 'selected' : ''); ?>>A to Z</option>
                                            <option value="z_to_a" <?php echo e(request('sort_project_name') == 'z_to_a' ? 'selected' : ''); ?>>Z to A</option>
                                        </select>
                                    </form>
                                </div>
                            </th>
                            <th>
                                Start Date
                                <a href="#" onclick="toggleFilter('date-filter')">
                                 <img src="frontend/images/bars-filter.png" alt="" class="barfilter">
                                </a>
                                <div id="date-filter" class="filter-dropdown" style="display: none;">
                                    <form action="<?php echo e(route('projects.index')); ?>" method="GET">
                                        <select name="sort_start_date" onchange="this.form.submit()">
                                            <option value="">Sort by Start Date</option>
                                            <option value="recent" <?php echo e(request('sort_start_date') == 'recent' ? 'selected' : ''); ?>>Most Recent</option>
                                            <option value="oldest" <?php echo e(request('sort_start_date') == 'oldest' ? 'selected' : ''); ?>>Oldest</option>
                                        </select>
                                    </form>
                                </div>
                            </th>
                            <th>
                                Due Date
                                <a href="#" onclick="toggleFilter('due-date-sort')">
                                 <img src="frontend/images/bars-filter.png" alt="" class="barfilter">
                                </a>
                                <div id="due-date-sort" class="filter-dropdown" style="display: none;">
                                    <form action="<?php echo e(route('projects.index')); ?>" method="GET">
                                        <select name="sort_due_date" onchange="this.form.submit()">
                                            <option value="">Sort by Due Date</option>
                                            <option value="more_time" <?php echo e(request('sort_due_date') == 'more_time' ? 'selected' : ''); ?>>Less Time</option>
                                            <option value="less_time" <?php echo e(request('sort_due_date') == 'less_time' ? 'selected' : ''); ?>>More Time</option>
                                        </select>
                                    </form>
                                </div>
                            </th>
                            <th>
                                Status
                                <a href="#" onclick="toggleFilter('status-filter')">
                                    <img src="frontend/images/bars-filter.png" alt="" class="barfilter">
                                </a>
                                <div id="status-filter" class="filter-dropdown" style="display: none;">
                                    <form action="<?php echo e(route('projects.index')); ?>" method="GET">
                                        <select name="filter_status" onchange="this.form.submit()">
                                            <option value="">All Status</option>
                                            <option value="Design" <?php echo e(request('filter_status') == 'Design' ? 'selected' : ''); ?>>Design</option>
                                            <option value="Development" <?php echo e(request('filter_status') == 'Development' ? 'selected' : ''); ?>>Development</option>
                                            <option value="QA" <?php echo e(request('filter_status') == 'QA' ? 'selected' : ''); ?>>QA</option>
                                            <option value="Content Fillup" <?php echo e(request('filter_status') == 'ContentFillup' ? 'selected' : ''); ?>>Content Fillup</option>
                                            <option value="Completed" <?php echo e(request('filter_status') == 'Completed' ? 'selected' : ''); ?>>Completed</option>
                                            <option value="Closed" <?php echo e(request('filter_status') == 'Closed' ? 'selected' : ''); ?>>Closed</option>
                                            <option value="Other" <?php echo e(request('filter_status') == 'Other' ? 'selected' : ''); ?>>Other</option>
                                        </select>
                                    </form>
                                </div>
                            </th>

                            <th>
                                Time Left
                                <a href="#" onclick="toggleFilter('time-left-sort')">
                                    <img src="frontend/images/bars-filter.png" alt="" class="barfilter">
                                </a>
                                <div id="time-left-sort" class="filter-dropdown" style="display: none;">
                                    <form action="<?php echo e(route('projects.index')); ?>" method="GET">
                                        <select name="sort_time_left" onchange="this.form.submit()">
                                            <option value="">Sort by Time Left</option>
                                            <option value="time_left_asc" <?php echo e(request('sort_time_left') == 'time_left_asc' ? 'selected' : ''); ?>>Less Days Left</option>
                                            <option value="time_left_desc" <?php echo e(request('sort_time_left') == 'time_left_desc' ? 'selected' : ''); ?>>More Days Left</option>
                                        </select>
                                    </form>
                                </div>
                            </th>


                                <th>Edit</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($key + 1); ?></td>
                            <td><a href="javascript:void(0);" onclick="openTaskDetailsModal(<?php echo e(json_encode($project)); ?>)"><?php echo e($project->name); ?></a></td>


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
                                <button class="btn-create" onclick="openAddTaskModal(<?php echo e($project->id); ?>)">Add Task</button>
                                <button class="btn-edit" onclick="openEditProjectModal(<?php echo e(json_encode($project)); ?>)">Edit</button>
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
                <div class="create-project">
                    <button onclick="openCreateProjectModal()">Create Project</button>
                </div>
            </div>
        </div>

        <!-- Modal for Creating New Project -->
        <div id="create-project-modal" style="display: none;">
            <div>
                <h3>Create New Project</h3>
                <form action="<?php echo e(route('projects.store')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <label for="project-name">Project Name:</label>
                    <input type="text" name="name" id="project-name" >

                    <label for="start-date">Start Date:</label>
                    <input type="date" name="start_date" id="start_date" >

                    <label for="due-date">Due Date:</label>
                    <input type="date" name="due_date" id="due_date" >

                    <label for="assignee">Status</label>
                    <select name="status" id="status" >
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
                    <input type="date" name="start_date" id="edit-start_date" >

                    <label for="edit-due-date">Due Date:</label>
                    <input type="date" name="due_date" id="edit-due_date" >

                    <label for="assignee">Status</label>
                    <select name="status" id="edit-status" >
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
                    <option value="<?php echo e($user->email); ?>"><?php echo e($user->name); ?> (<?php echo e($user->email); ?>)</option>
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
        function openCreateProjectModal() {document.getElementById('create-project-modal').style.display = 'block';}
        function closeCreateProjectModal() {document.getElementById('create-project-modal').style.display = 'none';}
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

            function toggleFilter(filterId) {
    // Close all other filters first
    document.querySelectorAll('.filter-dropdown').forEach(dropdown => {
        if (dropdown.id !== filterId) {
            dropdown.style.display = 'none';
        }
    });

    // Toggle the selected filter dropdown
    const element = document.getElementById(filterId);
    if (element) {
        element.style.display = (element.style.display === 'none' || element.style.display === '') ? 'block' : 'none';
    }
}

// Close dropdowns when clicking outside
window.onclick = function(event) {
    if (!event.target.matches('.barfilter') && !event.target.closest('.filter-dropdown')) {
        document.querySelectorAll('.filter-dropdown').forEach(dropdown => {
            dropdown.style.display = 'none';
        });
    }
};

    
    </script>  
        

    </main>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('frontends.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\Oct 29- Live edited-project management\resources\views/frontends/projects.blade.php ENDPATH**/ ?>