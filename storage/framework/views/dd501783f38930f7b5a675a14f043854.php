<?php $__env->startSection('main-container'); ?>
<main>
    <div class="profile-page">
    <div class="users-tasks">
            <?php
                $loggedInUser = Auth::user()->username; // Get the logged-in user's username
            ?>

            <?php $__currentLoopData = ['Sabin', 'Anubhav', 'Lokendra', 'Denisha', 'Muskaan', 'Jeena', 'Sabita', 'Gaurav', 'Sudeep Sir', 'Suraj Sir']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $username): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <span class="user-span <?php echo e($username === $loggedInUser ? 'active' : ''); ?>" data-username="<?php echo e($username); ?>">
                    <?php echo e($username); ?>

                </span>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                                <td><textarea><?php echo e($task->comment); ?></textarea></td>
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
    </div>
   

    <!-- ----- My Schedule  ----  -->
    <!-- <div class="myschedule"> 
    <!--    <div class="schedule-heading">-->
    <!--        <h2>October 15</h2>-->
    <!--    </div>-->
    <!--    <table>-->
    <!--        <thead>-->
    <!--            <tr>-->
    <!--                <th>Time</th>-->
    <!--                <th>Task</th>-->
    <!--                <th>Min</th>-->
    <!--            </tr>-->
    <!--        </thead>-->
    <!--        <tbody>-->
    <!--            <tr>-->
    <!--                <td>10:00 - 10:30 AM</td>-->
    <!--                <td>Design</td>-->
    <!--                <td>30 min</td>-->
    <!--            </tr>-->
    <!--            <tr>-->
    <!--                <td>10:30 - 11:00 AM</td>-->
    <!--                <td>Development</td>-->
    <!--                <td>30 min</td>-->
    <!--            </tr>-->
    <!--            <tr>-->
    <!--                <td>11:00 - 11:30 AM</td>-->
    <!--                <td>Testing</td>-->
    <!--                <td>30 min</td>-->
    <!--            </tr>-->
    <!--            <tr>-->
    <!--                <td>11:30 - 12:00 PM</td>-->
    <!--                <td>Code Review</td>-->
    <!--                <td>30 min</td>-->
    <!--            </tr>-->
    <!--            <tr>-->
    <!--                <td>12:00 - 12:30 PM</td>-->
    <!--                <td>Meeting</td>-->
    <!--                <td>30 min</td>-->
    <!--            </tr>-->
    <!--            <tr>-->
    <!--                <td>12:30 - 1:00 PM</td>-->
    <!--                <td>Documentation</td>-->
    <!--                <td>30 min</td>-->
    <!--            </tr>-->
    <!--            <tr>-->
    <!--                <td>1:00 - 1:30 PM</td>-->
    <!--                <td>Lunch Break</td>-->
    <!--                <td>30 min</td>-->
    <!--            </tr>-->
    <!--            <tr>-->
    <!--                <td>1:30 - 2:00 PM</td>-->
    <!--                <td>Design Review</td>-->
    <!--                <td>30 min</td>-->
    <!--            </tr>-->
    <!--            <tr>-->
    <!--                <td>2:00 - 2:30 PM</td>-->
    <!--                <td>Client Call</td>-->
    <!--                <td>30 min</td>-->
    <!--            </tr>-->
    <!--            <tr>-->
    <!--                <td>2:30 - 3:00 PM</td>-->
    <!--                <td>Feature Development</td>-->
    <!--                <td>30 min</td>-->
    <!--            </tr>-->
    <!--            <tr>-->
    <!--                <td>3:00 - 3:30 PM</td>-->
    <!--                <td>Bug Fixing</td>-->
    <!--                <td>30 min</td>-->
    <!--            </tr>-->
    <!--            <tr>-->
    <!--                <td>3:30 - 4:00 PM</td>-->
    <!--                <td>Testing</td>-->
    <!--                <td>30 min</td>-->
    <!--            </tr>-->
    <!--            <tr>-->
    <!--                <td>4:00 - 4:30 PM</td>-->
    <!--                <td>Refactoring Code</td>-->
    <!--                <td>30 min</td>-->
    <!--            </tr>-->
    <!--            <tr>-->
    <!--                <td>4:30 - 5:00 PM</td>-->
    <!--                <td>Prepare Presentation</td>-->
    <!--                <td>30 min</td>-->
    <!--            </tr>-->
    <!--            <tr>-->
    <!--                <td>5:00 - 5:30 PM</td>-->
    <!--                <td>Team Sync</td>-->
    <!--                <td>30 min</td>-->
    <!--            </tr>-->
    <!--            <tr>-->
    <!--                <td>5:30 - 6:00 PM</td>-->
    <!--                <td>Wrap Up Tasks</td>-->
    <!--                <td>30 min</td>-->
    <!--            </tr>-->
    <!--        </tbody>-->
    <!--    </table>-->
    <!--</div> -->-->

    <?php if(Auth::check() && Auth::user()->email == $user->email): ?>
            <div class="edit-logout">
                
                <div class="logout">
                    <a href="<?php echo e(route('logout')); ?>">Logout</a>
                </div>
            </div>
        <?php endif; ?>


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
                        <td><textarea>${task.comment}</textarea></td>
                    </tr>`;
                });

                tableHtml += `</tbody></table>`;
                tasksContainer.innerHTML += tableHtml; // Append the table to the tasks container
            } else {
                tasksContainer.innerHTML += `<p>No tasks available for ${username}.</p>`;
            }
        }
    });


    </script>
</main>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontends.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\Oct 29- Live edited-project management\resources\views/frontends/dashboard.blade.php ENDPATH**/ ?>