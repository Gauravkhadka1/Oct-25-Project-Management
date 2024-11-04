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

            <?php if(auth()->check() && in_array(auth()->user()->email, $allowedEmails)): ?>
            <?php $__currentLoopData = ['Sabin', 'Anubhav', 'Lokendra', 'Denisha', 'Muskaan', 'Jeena', 'Sabita', 'Gaurav', 'Sudeep Sir', 'Suraj Sir']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $username): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <span class="user-span <?php echo e($username === $loggedInUser ? 'active' : ''); ?>" data-username="<?php echo e($username); ?>">
                <?php echo e($username); ?>

            </span>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
        </div>


        <div class="mytasks">
            <div class="current-tasks">
                <h2><?php echo e($loggedInUser); ?> Tasks</h2>
                <?php
                $hasTasks = false; // Flag to check if there are any tasks
                $serialNo = 1;
                ?>

                <?php $__empty_1 = true; $__currentLoopData = $projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php if(!$project->tasks->isEmpty()): ?>
                <?php if(!$hasTasks): ?>
                <table class="task-table">
                    <thead>
                        <tr>
                            <th>S.N</th>
                            <th>Task</th>
                            <th>Project</th>
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
                        <?php endif; ?>

                        <?php $__currentLoopData = $project->tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php $hasTasks = true; ?>
                        <tr>
                            <td><?php echo e($serialNo++); ?></td>
                            <td><?php echo e($task->name); ?></td>
                            <td><?php echo e($project->name); ?></td>
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
                            <td>
                                <button class="btn-add-activity" onclick="openAddActivityModal"><img src="<?php echo e(url ('frontend/images/plus.png')); ?>" alt=""></button>
                                <button class="btn-view-activities" onclick="viewComments"><img src="<?php echo e(url ('frontend/images/view.png')); ?>" alt=""></button>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        <?php if(!$hasTasks): ?>
                    </tbody>
                </table>
                <?php endif; ?>
                <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p>No projects available.</p>
                <?php endif; ?>

                <?php if($hasTasks): ?>
                </tbody>
                </table>
                <?php endif; ?>

            </div>

        </div>

        <!-- Add Activity Modal -->
    <div id="add-comments-modal" class="modal">
        <div class="modal-content">
            <h3>Add Activity</h3>
            <form id="add-comments-form" action="" method="POST">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="project_id" id="comments-project-id">

                <label for="comments-details">Activity Details:</label>
                <input type="text" name="comments" id="comments-details" required>

                <div class="modal-buttons">
                    <button type="submit" class="btn-submit">Add Activity</button>
                    <button type="button" class="btn-cancel" onclick="closeAddCommentsModal()">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <!-- View Activities Modal -->
    <div id="view-activities-modal" class="modal">
        <div class="modal-content">
            <h3>Activities</h3>
            <div id="activities-list">
                <!-- Activities will be populated here -->
            </div>
            <div class="modal-buttons">
                <button type="button" class="btn-cancel" onclick="closeViewCommentsModal()">Close</button>
            </div>
        </div>
    </div>

    </div>


    <script>
        let timers = {};

        // Load the saved time from the database when the page loads
        window.addEventListener("load", () => {
            <?php $__currentLoopData = $projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php $__currentLoopData = $project -> tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            if (!timers[{
                    {
                        $task -> id
                    }
                }]) {
                timers[{
                    {
                        $task -> id
                    }
                }] = {
                    elapsedTime: {
                        {
                            $task -> elapsed_time * 1000
                        }
                    },
                    running: false
                };
            }
            console.log("Timer initialized for task ID:", {
                {
                    $task -> id
                }
            });
            updateTimerDisplay({
                {
                    $task -> id
                }
            });
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
                    body: JSON.stringify({
                        elapsed_time: elapsedTime
                    })
                })
                .then(response => response.json())
                .then(data => console.log(`Timer updated: ${data.message}`))
                .catch(error => console.error('Error updating timer:', error));
        }



        document.addEventListener("DOMContentLoaded", function() {
            const userSpans = document.querySelectorAll('.user-span');

            userSpans.forEach(span => {
                span.addEventListener('click', function() {
                    // Remove 'active' class from all spans
                    userSpans.forEach(s => s.classList.remove('active'));

                    // Add 'active' class to the clicked span
                    this.classList.add('active');

                    const selectedUsername = this.dataset.username;
                    showTasksForUser(selectedUsername);
                });
            });

            function showTasksForUser(username) {
                // Here you would typically make an AJAX call to fetch tasks for the selected user
                fetch(`/tasks?username=${username}`)
                    .then(response => response.json())
                    .then(tasks => {
                        updateTaskDetails(tasks, username);
                    })
                    .catch(error => console.error('Error fetching tasks:', error));
            }

            function updateTaskDetails(tasks, username) {
                const tasksContainer = document.querySelector('.current-tasks');
                tasksContainer.innerHTML = `<h2>${username} Tasks</h2>`; // Update the header with the selected username

                if (tasks.length > 0) {
                    let tableHtml = `<table class="task-table">
                    <thead>
                        <tr>
                            <th>S.N</th>
                            <th>Task</th>
                            <th>Project</th>
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
                    <tbody>`;

                    tasks.forEach((task, index) => {
                        tableHtml += `<tr>
                        <td>${index + 1}</td>
                        <td>${task.name}</td>
                        <td>${task.project_name}</td>
                        <td>${task.assignedBy ? task.assignedBy.username : 'N/A'}</td>
                        <td>${task.start_date}</td>
                        <td>${task.due_date}</td>
                        <td>${task.priority}</td>
                        <td>
                            <button class="btn-toggle start" id="toggle-${task.id}" onclick="toggleTimer(${task.id})">Start</button>
                        </td>
                        <td id="time-${task.id}">00:00:00</td>
                        <td>
                            <select name="status">
                                <option>To Do</option>
                                <option>In Progress</option>
                                <option>QA</option>
                                <option>Completed</option>
                            </select>
                        </td>
                        <td>
                                <button class="btn-add-comments" onclick="openAddActivityModal"><img src="<?php echo e(url ('frontend/images/plus.png')); ?>" alt=""></button>
                                <button class="btn-view-comments" onclick="viewActivities"><img src="<?php echo e(url ('frontend/images/view.png')); ?>" alt=""></button>
                            </td>
                    </tr>`;
                    });

                    tableHtml += `</tbody></table>`;
                    tasksContainer.innerHTML += tableHtml; // Append the table to the tasks container
                } else {
                    tasksContainer.innerHTML += `<p>No tasks available for ${username}.</p>`;
                }
            }
        });


        // add/view comments
        // activities model
        function openAddActivityModal(paymentsId) {
            document.getElementById('activity-payments-id').value = paymentsId;
            document.getElementById('add-activity-modal').style.display = 'block';
        }

        // close add activity model
        function closeAddActivityModal() {
            document.getElementById('add-activity-modal').style.display = 'none';
        }

        function viewActivities(paymentsId) {
            // Fetch activities from the server for the given payments
            fetch(`/payments/${paymentsId}/activities`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    console.log(data); // Log the entire response
                    const activitiesList = document.getElementById('activities-list');
                    activitiesList.innerHTML = ''; // Clear previous activities

                    if (data.activities && data.activities.length > 0) {
                        data.activities.forEach(activity => {
                            const utcDate = new Date(activity.created_at); // Get the date in UTC
                            const localTime = utcDate;

                            // Formatting options for day, date, and time
                            const dateOptions = {
                                weekday: 'long',
                                year: 'numeric',
                                month: '2-digit',
                                day: '2-digit'
                            };
                            const timeOptions = {
                                hour: '2-digit',
                                minute: '2-digit',
                                hour12: true
                            };

                            const formattedDate = localTime.toLocaleDateString('en-NP', dateOptions);
                            const formattedTime = localTime.toLocaleTimeString('en-US', timeOptions).replace(/^0/, '');

                            // Use a fallback for undefined replies
                            const replies = activity.replies || [];

                            // Create a card-like structure for each activity with like and reply functionality
                            const activityCard = document.createElement('div');
                            activityCard.className = 'activity-card';
                            activityCard.innerHTML = `
                        <p>${formattedDate} ${formattedTime}</p>
                        <p><strong>${activity.username}</strong>: ${activity.details}</p>
                    `;
                            activitiesList.appendChild(activityCard); // Append the card to the list
                        });
                    } else {
                        activitiesList.innerHTML = '<p>No activities found.</p>'; // Message if no activities
                    }

                    // Display the modal
                    document.getElementById('view-activities-modal').style.display = 'block';
                })
                .catch(error => console.error('Error fetching activities:', error));
        }

        // close view activities model
        function closeViewActivitiesModal() {
            document.getElementById('view-activities-modal').style.display = 'none';
        }
        // add/view comments end
    </script>
</main>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('frontends.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\Oct 29- Live edited-project management\resources\views/frontends/dashboard.blade.php ENDPATH**/ ?>