<?php $__env->startSection('main-container'); ?>
<?php if(session('success')): ?>
<div id="success-message" style="position: fixed; top: 20%; left: 50%; transform: translate(-50%, -50%); background-color: #28a745; color: white; padding: 15px; border-radius: 5px; z-index: 9999;">
    <?php echo e(session('success')); ?>

</div>

<?php endif; ?>

<div id="success-message" style="display: none; position: fixed; top: 20%; left: 50%; transform: translate(-50%, -50%); background-color: #28a745; color: white; padding: 15px; border-radius: 5px; z-index: 9999;">
    <!-- Success message will be dynamically inserted here -->
</div>
<div class="project-task-detail-page">
    <div class="task-details">
        <div class="ptask-name">
            <p><?php echo e($project->name); ?>/ <?php echo e($task->name); ?></p>
        </div>
        <div class="tasks-in-details">
            <div class="task-details-one">
                <div class="task-status">
                 <p class="labels">Status:</p>  <p class="datas"><?php echo e($task->status ?? 'TO DO'); ?></p>
                </div>
                <div class="task-start-date">
                <p class="labels">Start Date:</p><p class="datas"><?php echo e($task->start_date); ?></p> 
                </div>
                <div class="task-due-date">
                <p class="labels">Due Date:</p> <p class="datas"><?php echo e($task->due_date); ?></p> 
                </div>
               
            </div>
            <div class="task-details-two">
                <div class="task-assignedto">
                <p class="labels"> Assigned to:</p> 
                   
                    <img
                        src="<?php echo e($task->assignedTo->profilepic ? asset('storage/profilepics/' . $task->assignedTo->profilepic) : asset('images/default-profile.png')); ?>"
                        alt="<?php echo e($task->assignedTo->name); ?>'s Profile Picture"
                        class="user-profile-pic">
                </div>
                <div class="task-assignedto">
                <p class="labels">  Assigned By:</p> 
                   
                    <img
                        src="<?php echo e($task->assignedBy->profilepic ? asset('storage/profilepics/' . $task->assignedBy->profilepic) : asset('images/default-profile.png')); ?>"
                        alt="<?php echo e($task->assignedBy->name); ?>'s Profile Picture"
                        class="user-profile-pic">
                </div>
                <div class="task-due-date">

                </div>
            </div>
        </div>
    </div>
    <div class="pcomments">
        <div class="project-task-comments">
            <h2>Comments</h2>
            <div id="activities-list">
                <!-- Activities will be dynamically loaded here -->
            </div>

            <!-- Sticky Add Activity Section -->
            <div id="add-activity-section" class="sticky-section">
                <form id="add-activity-form" action="<?php echo e(route('projectsActivities.store')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="task_id" id="activity-task-id" value="<?php echo e($task->id ?? ''); ?>">
                    <input type="hidden" name="project_id" value="<?php echo e($task->project->id); ?>">

                    <input type="text" name="comments" id="activity-details" placeholder="Add Comments..." required>
                    <div id="suggestions"></div>
                    <div class="form-buttons">
                        <button type="submit" class="btn-submit">Add
                            <div id="loading-spinner" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 9999;">
                                <img src="<?php echo e(url('public/frontend/images/spinner.gif')); ?>" alt="Loading...">
                            </div>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const taskId = document.getElementById('activity-task-id').value;

            // Fetch and display activities
            fetch(`/projecttasks/${taskId}/activities`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Failed to fetch activities');
                    }
                    return response.json();
                })
                .then(data => {
                    const activitiesList = document.getElementById('activities-list');
                    activitiesList.innerHTML = ''; // Clear previous activities

                    if (data.activities && data.activities.length > 0) {
                        data.activities.forEach(activity => {
                            const utcDate = new Date(activity.created_at);
                            const localTime = utcDate.toLocaleString('en-US', {
                                month: 'short', // Displays abbreviated month (e.g., Nov)
                                day: 'numeric', // Displays the day (e.g., 11)
                                hour: 'numeric', // Displays the hour (e.g., 8)
                                minute: '2-digit', // Displays the minute with two digits (e.g., 50)
                                hour12: true // Ensures 12-hour format (e.g., pm)
                            }).replace(',', ' at'); // Replace the default comma with "at"

                            // Highlight mentioned usernames
                            const details = activity.details || ''; // Use empty string if details are undefined
                            const highlightedDetails = activity.details.replace(
                                /@(\w+)/g, // Regex to find @username
                                `<strong>@$1</strong>` // Wrap username in <strong>
                            );

                            // Create a card for each activity
                            const profilePic = activity.profile_pic ?
                                `/storage/${activity.profile_pic}` // Correct path
                                :
                                '/images/default-profile.png'; // Fallback image

                            // Create a card for each activity
                            const activityCard = document.createElement('div');
                            activityCard.className = 'activity-card';
                            activityCard.innerHTML = `
                        <div class="activity-header">
                            <div class="usernameandpic">
                               <img src="${profilePic}" alt="Profile Picture" class="profile-pic">
                               <p class="username"><strong>${activity.username}</strong></p>
                            </div>
                            <div class="activity-meta">
                                <p class="timestamp">${localTime}</p>
                            </div>
                        </div>
                        
                         <div class="activity-comment">
                            <p>${highlightedDetails}</p>
                        </div>
                    `;
                            activitiesList.appendChild(activityCard);
                        });
                    } else {
                        activitiesList.innerHTML = '<p>No comments found.</p>';
                    }
                })
                .catch(error => console.error('Error loading activities:', error));
        });


        // mention script

        $(document).ready(function() {
            // Detect '@' and fetch suggestions
            $('#activity-details').on('keyup', function(e) {
                const value = $(this).val();
                const atIndex = value.lastIndexOf('@');

                // Show suggestions when '@' is typed and it's the last character
                if (atIndex !== -1 && (value.length === atIndex + 1)) {
                    $.ajax({
                        url: '/api/users/search',
                        method: 'GET',
                        data: {
                            query: ''
                        }, // Empty query fetches all usernames
                        success: function(data) {
                            $('#suggestions').empty();
                            if (data.length > 0) {
                                data.forEach(function(user) {
                                    $('#suggestions').append(`
                                <div data-username="${user.username}" class="suggestion-item">
                                    <img src="${user.profilepic ? '/storage/profilepics/' + user.profilepic : '/images/default-profile.png'}" 
                                         alt="${user.username}'s Profile Picture" class="suggestion-pic">
                                    <span>${user.username}</span>
                                </div>
                            `);
                                });
                                $('#suggestions').show();
                            } else {
                                $('#suggestions').hide();
                            }
                        }
                    });
                } else if (atIndex !== -1) {
                    const query = value.substring(atIndex + 1);
                    if (query.length > 0) {
                        $.ajax({
                            url: '/api/users/search',
                            method: 'GET',
                            data: {
                                query: query
                            },
                            success: function(data) {
                                $('#suggestions').empty();
                                if (data.length > 0) {
                                    data.forEach(function(user) {
                                        $('#suggestions').append(`
                                <div data-username="${user.username}" class="suggestion-item">
                                    <img src="${user.profilepic ? '/storage/profilepics/' + user.profilepic : '/images/default-profile.png'}" 
                                         alt="${user.username}'s Profile Picture" class="suggestion-pic">
                                    <span>${user.username}</span>
                                </div>
                            `);
                                    });
                                    $('#suggestions').show();
                                } else {
                                    $('#suggestions').hide();
                                }
                            }
                        });
                    } else {
                        $('#suggestions').hide();
                    }
                } else {
                    $('#suggestions').hide();
                }
            });

            // Add username to message when clicked from suggestions
            $(document).on('click', '#suggestions div', function() {
                const username = $(this).data('username');
                const message = $('#activity-details').val();
                const atIndex = message.lastIndexOf('@');

                const fullMessage = message.substring(0, atIndex + 1) + username + ' ';
                $('#activity-details').val(fullMessage);
                $('#suggestions').hide();
            });

            // Form submission handler
            $('#add-activity-form').on('submit', function(e) {
                e.preventDefault(); // Prevent default form submission

                const projectId = $('#project-id').val();
                const taskId = $('#activity-task-id').val(); // Fetch task_id value
                const comments = $('#activity-details').val(); // Fetch comments value
                const message = $('#activity-details').val();
                const mentionedUser = extractMentionedUser(message); // Extract mentioned user


                // Show loading spinner
                $('#loading-spinner').show();

                $.ajax({
                    url: $(this).attr('action'), // Use form's action URL
                    method: 'POST',
                    data: {
                        project_id: projectId,
                        task_id: taskId,
                        comments: comments,
                        mentioned_user: mentionedUser, // Include mentioned user
                        _token: $('input[name="_token"]').val() // CSRF token
                    },
                    success: function(response) {
                        // Dynamically display success message
                        $('#success-message').text('Activity added and notification sent successfully!').show();

                        // Clear input field and hide suggestions
                        $('#activity-details').val('');
                        $('#suggestions').hide();

                        // Hide the success message after 5 seconds
                        setTimeout(function() {
                            $('#success-message').fadeOut();
                        }, 5000);

                        // Hide loading spinner
                        $('#loading-spinner').hide();
                    },
                    error: function(xhr) {
                        console.error('Error submitting message:', xhr.responseText); // Log errors

                        // Hide loading spinner
                        $('#loading-spinner').hide();
                    }
                });
            });

            // Submit message on Enter keypress
            $('#activity-details').on('keypress', function(e) {
                if (e.which === 13) { // Enter key
                    e.preventDefault(); // Prevent default new line
                    $('#add-activity-form').submit(); // Trigger form submission
                }
            });

            // Hide suggestions when clicking outside
            $(document).click(function(e) {
                if (!$(e.target).closest('#suggestions, #activity-details').length) {
                    $('#suggestions').hide();
                }
            });
        });

        // Helper function to extract the mentioned user from the message
        function extractMentionedUser(message) {
            const mentionRegex = /@(\w+)/; // Regex to match @username
            const match = message.match(mentionRegex);

            if (match && match[1]) {
                return match[1]; // Return the username or identifier
            }
            return null; // Return null if no mention found
        }
    </script>
    <?php $__env->stopSection(); ?>
<?php echo $__env->make('frontends.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\Oct 29- Live edited-project management\resources\views/frontends/taskdetail.blade.php ENDPATH**/ ?>