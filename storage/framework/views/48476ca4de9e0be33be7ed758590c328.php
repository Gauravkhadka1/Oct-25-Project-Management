<?php $__env->startSection('main-container'); ?>

<main>
    <?php if(session('success')): ?>
    <div id="success-message" style="position: fixed; top: 20%; left: 50%; transform: translate(-50%, -50%); background-color: #28a745; color: white; padding: 15px; border-radius: 5px; z-index: 9999;">
        <?php echo e(session('success')); ?>

    </div>

    <?php endif; ?>

    <div class="prospects-list">
        <div class="prospect-heading">
            <div class="prospect-heading">
                <h2>Prospects</h2>
            </div>
        </div>
        <div class="create-filter-search">
            <div class="create-prospect">
                <button class="btn-create" onclick="openCreateProspectModal()"><img src="<?php echo e(url ('/frontend/images/add.png')); ?>" alt=""> Prospect</button>
            </div>
            <div class="filter-section">
                <div class="filter-prospects" onclick="toggleFilterList()">
                    <img src="frontend/images/bars-filter.png" alt="" class="barfilter">
                    <div class="filter-count">
                        <?php if($filterCount > 0): ?>
                        <p><?php echo e($filterCount); ?></p>
                        <?php endif; ?>
                    </div>
                    Filter
                </div>
                <div class="filter-options" style="display: none;">
                    <form action="<?php echo e(route('prospects.index')); ?>" method="GET">
                        <!-- Inquiry Date Filter -->
                        <div class="filter-item">
                            <label for="inquiry-date">Inquiry Date:</label>
                            <select id="inquiry-date" name="inquiry_date" class="filter-select" onchange="handleDateRange(this)">
                                <option value="">Select Options</option>
                                <option value="recent" <?php echo e(request('inquiry_date') == 'recent' ? 'selected' : ''); ?>>Recent</option>
                                <option value="oldest" <?php echo e(request('inquiry_date') == 'oldest' ? 'selected' : ''); ?>>Oldest</option>
                                <option value="date-range" <?php echo e(request('inquiry_date') == 'date-range' ? 'selected' : ''); ?>>Choose Date Range</option>
                            </select>
                            <div id="date-range-picker" class="date-range-picker" style="display: <?php echo e(request('inquiry-date') == 'date-range' ? 'block' : 'none'); ?>">
                                <label for="from-date">From:</label>
                                <input type="date" id="from-date" name="from_date" value="<?php echo e(request('from_date')); ?>">
                                <label for="to-date">To:</label>
                                <input type="date" id="to-date" name="to_date" value="<?php echo e(request('to_date')); ?>">
                            </div>
                        </div>

                        <!-- Category Filter -->
                        <div class="filter-item">
                            <label for="category">Category:</label>
                            <select id="category" name="filter_category" class="filter-select">
                                <option value="">Select Options</option>
                                <option value="website" <?php echo e(request('filter_category') == 'website' ? 'selected' : ''); ?>>Website</option>
                                <option value="microsoft" <?php echo e(request('filter_category') == 'microsoft' ? 'selected' : ''); ?>>Microsoft</option>
                                <option value="other" <?php echo e(request('filter_category') == 'other' ? 'selected' : ''); ?>>Other</option>
                            </select>
                        </div>

                        <!-- Status Filter -->
                        <div class="filter-item">
                            <label for="status">Status:</label>
                            <select id="status" name="sort_status" class="filter-select">
                                <option value="">Select Options</option>
                                <option value="Not Responded" <?php echo e(request('sort_status') == 'Not Responded' ? 'selected' : ''); ?>>Not Responded</option>
                                <option value="dealing" <?php echo e(request('sort_status') == 'dealing' ? 'selected' : ''); ?>>Dealing</option>
                                <option value="converted" <?php echo e(request('sort_status') == 'converted' ? 'selected' : ''); ?>>Converted</option>
                            </select>
                        </div>

                        <button type="submit">Apply Filter</button>
                    </form>
                </div>

            </div>
            <div class="search-prospects">
                <div class="search-icon">
                    <img src="frontend/images/search-icon.png" alt="" class="searchi-icon">
                </div>
                <form action="<?php echo e(route('prospects.index')); ?>" method="GET" id="search-form">
                    <div class="search-text-area">
                        <input type="text" name="search" placeholder="search..." value="<?php echo e(request('search')); ?>" oninput="this.form.submit()">
                    </div>
                </form>
            </div>

        </div>
        <table class="modern-payments-table">
            <thead>
                <tr>
                    <th>SN</th>
                    <th>
                        Company Name
                    </th>
                    <th>
                        Category
                    </th>
                    <th>
                        Inquiry Date
                    </th>
                    <th>
                        Probability
                    </th>
                    <th>Tasks</th>
                    <th>Activities</th>
                    <th>
                        Status
                    </th>
                    <th>Edit</th>
                </tr>
            </thead>

            <tbody>
                <?php $__currentLoopData = $prospects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $prospect): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($key + 1); ?></td>
                    <td>
                        <span class="prospect-name"><?php echo e($prospect->company_name); ?></span>
                        <button class="btn-see-details" data-contact_person="<?php echo e($prospect->contact_person); ?>" data-phone="<?php echo e($prospect->phone_number); ?>" data-email="<?php echo e($prospect->email); ?>" data-address="<?php echo e($prospect->address); ?>" data-message="<?php echo e($prospect->message); ?>">
                            <div class="btn-see-detail-img">
                                <img src="<?php echo e(url ('/frontend/images/info.png')); ?>" alt="">
                            </div>
                        </button>
                    </td>


                    <td><?php echo e($prospect->category); ?></td>

                    <td><?php echo e($prospect->inquirydate ? $prospect->inquirydate->format('Y-m-d h:i A') : 'N/A'); ?></td>
                    <td><?php echo e($prospect->probability); ?>%</td>
                    <td>
                        <button class="btn-create" id="task-create" onclick="openAddTaskModal(<?php echo e($prospect->id); ?>)"><img src="<?php echo e(url ('/frontend/images/plus.png')); ?>" alt=""> Task</button>
                        <button class="btn-view-activities-p" onclick="openTaskDetailsModal(<?php echo e(json_encode($prospect)); ?>)"><img src="<?php echo e(url ('/frontend/images/view.png')); ?>" alt="">Tasks</button>
                    </td>
                    <td>
                        <button class="btn-add-activity" onclick="openAddActivityModal(<?php echo e($prospect->id); ?>)"><img src="<?php echo e(url ('/frontend/images/plus.png')); ?>" alt=""></button>
                        <button class="btn-view-activities" onclick="viewActivities(<?php echo e($prospect->id); ?>)"><img src="<?php echo e(url ('/frontend/images/view.png')); ?>" alt=""></button>
                    </td>


                    <td><?php echo e($prospect->status); ?></td>
                    <td>
                        <button class="btn-create" onclick="openEditProspectModal(<?php echo e(json_encode($prospect)); ?>)"><img src="<?php echo e(url ('/frontend/images/edit.png')); ?>" alt=""></button>
                        <form action="<?php echo e(route('prospects.destroy', $prospect->id)); ?>" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this prospect?');">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="btn-cancel"><img src="<?php echo e(url ('/frontend/images/delete.png')); ?>" alt=""></button>
                        </form>
                    </td>

                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>

    </div>

    <!-- details modals -->
    <!-- See Details Modal -->
    <div id="details-modal" class="details-modal" style="display: none;">
        <div class="details-modal-content">
            <h3>Prospect Details</h3>
            <p><strong>Contact person:</strong> <span id="modal-contact_person"></span></p>
            <p><strong>Phone:</strong> <span id="modal-phone"></span></p>
            <p><strong>Email:</strong> <span id="modal-email"></span></p>
            <p><strong>Address:</strong> <span id="modal-address"></span></p>
            <p><strong>Message:</strong> <span id="modal-message"></span></p>
            <div class="details-modal-buttons">
                <button type="button" class="btn-cancel" onclick="closeDetailsModal()">Close</button>
            </div>
        </div>
    </div>

    <!-- Modal for Task Details -->
    <div id="task-details-modal" class="modal" style="display: none;">
        <div class="modal-content">
            <span class="close" onclick="closeTaskDetailsModal()">&times;</span>
            <h3 id="prospect-name-modal">Prospects Tasks</h3>

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
            <form action="<?php echo e(route('prospectstasks.store')); ?>" method="POST" class="custom-form">
                <?php echo csrf_field(); ?>
                <input type="hidden" id="prospect-id" name="prospect_id" value="">

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
                <input type="datetime-local" name="start_date" id="start-date" class="custom-input">

                <label for="due-date" class="custom-label">Due Date:</label>
                <input type="datetime-local" name="due_date" id="due-date" class="custom-input">

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

    <!-- see more message modal -->
    <div id="message-modal" class="messagemodal" style="display: none;">
        <div class="messagemodal-content">
            <h3>Full Message</h3>
            <p id="full-message-content"></p> <!-- This will display the full message -->
            <div class="messagemodal-buttons">
                <button type="button" class="btn-cancel" onclick="closeMessageModal()">Close</button>
            </div>
        </div>
    </div>


    <!-- Create Prospect Modal -->
    <div id="create-prospect-modal" class="modal" style="display: none;">
        <div class="modal-content">
            <h3>Create New Prospect</h3>
            <form action="<?php echo e(route('prospects.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <label for="prospect-company_name">Company Name:</label>
                <input type="text" name="company_name" id="prospect-company_name" required><br>

                <label for="category">Category</label>
                <select name="category" id="category">
                    <option value="Ecommerce">Ecommerce</option>
                    <option value="NGO/ INGO">NGO/ INGO</option>
                    <option value="Tourism">Tourism</option>
                    <option value="Tourism">Informative</option>
                    <option value="Education">Education</option>
                    <option value="Microsoft">Microsoft</option>
                    <option value="Other">Other</option>
                </select><br>

                <label for="prospect-contact_person">Contact Person:</label>
                <input type="text" name="contact_person" id="prospect-contact_person"><br>

                <label for="phone_number">Phone Number:</label>
                <input type="text" name="phone_number" id="phone_number"><br>

                <label for="email">Email:</label>
                <input type="email" name="email" id="email"><br>

                <label for="email">Address:</label>
                <input type="text" name="address" id="address"><br>

                <label for="message">Message:</label>
                <textarea name="message" id="message"></textarea><br>

                <label for="probability">Probability</label>
                <input type="number" name="probability" id="probability"><br>

                <label for="inquirydate">Inquiry Date</label>
                <input type="datetime-local" name="inquirydate" id="inquirydate"><br>

                <label for="activities">Activities</label>
                <input type="text" name="activities" id="activities"><br>

                <label for="status">Status</label>
                <select name="status" id="status">
                    <option value="Not Responded">Not Responded</option>
                    <option value="Dealing">Dealing</option>
                    <option value="Converted">Converted</option>
                    <option value="Missed">Missed</option>
                </select><br>

                <div class="modal-buttons">
                    <button type="submit" class="btn-submit">Add Prospect</button>
                    <button type="button" class="btn-cancel" onclick="closeCreateProspectModal()">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Prospect Modal -->
    <div id="edit-prospect-modal" class="modal" style="display: none;">
        <div class="modal-content">
            <h3>Edit Prospect</h3>
            <form id="edit-prospect-form" action="<?php echo e(route('prospects.update', '')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                <label for="edit-prospect-company_name">Company Name:</label>
                <input type="text" name="company_name" id="edit-company_name"><br>

                <label for="edit-category">Category</label>
                <select name="category" id="edit-category">
                    <option value="Ecommerce">Ecommerce</option>
                    <option value="NGO/ INGO">NGO/ INGO</option>
                    <option value="Tourism">Tourism</option>
                    <option value="Education">Education</option>
                    <option value="Microsoft">Microsoft</option>
                    <option value="Other">Other</option>
                </select><br>

                <label for="edit-contact_person">Contact Person:</label>
                <input type="text" name="contact_person" id="edit-contact_person"><br>

                <label for="edit-phone_number">Phone Number:</label>
                <input type="text" name="phone_number" id="edit-phone_number"><br>

                <label for="edit-email">Email:</label>
                <input type="email" name="email" id="edit-email"><br>

                <label for="edit-email">Address:</label>
                <input type="text" name="address" id="edit-address"><br>

                <label for="edit-message">Message:</label>
                <textarea name="message" id="edit-message"></textarea><br>

                <label for="edit-probability">Probability</label>
                <input type="number" name="probability" id="edit-probability"><br>

                <label for="edit-inquirydate">Inquiry Date</label>
                <input type="datetime-local" name="inquirydate" id="edit-inquirydate"><br>

                <label for="edit-activities">Activities</label>
                <input type="text" name="activities" id="edit-activities"><br>

                <label for="edit-status">Status</label>
                <select name="status" id="edit-status">
                    <option value="Not Responded">Not Responded</option>
                    <option value="Dealing">Dealing</option>
                    <option value="Converted">Converted</option>
                    <option value="Missed">Missed</option>
                </select><br>

                <div class="modal-buttons">
                    <button type="submit" class="btn-submit">Update Prospect</button>
                    <button type="button" class="btn-cancel" onclick="closeEditProspectModal()">Cancel</button>
                </div>
            </form>
        </div>
    </div>


    <!-- Add Activity Modal -->
    <div id="add-activity-modal" class="modal">
        <div class="modal-content">
            <h3>Add Activity</h3>
            <form id="add-activity-form" action="<?php echo e(route('activities.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="prospect_id" id="activity-prospect-id">

                <label for="activity-details">Activity Details:</label>
                <input type="text" name="details" id="activity-details" required>
                <div id="mention-suggestions" style="display: none;"></div> <!-- For At.js suggestions -->

                <div class="modal-buttons">
                    <button type="submit" class="btn-submit">Add Activity</button>
                    <button type="button" class="btn-cancel" onclick="closeAddActivityModal()">Cancel</button>
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
                <button type="button" class="btn-cancel" onclick="closeViewActivitiesModal()">Close</button>
            </div>
        </div>
    </div>


    <script>
        // Get CSRF token from the meta tag
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');


        function openCreateProspectModal() {
            document.getElementById('create-prospect-modal').style.display = 'block';
        }

        function closeCreateProspectModal() {
            document.getElementById('create-prospect-modal').style.display = 'none';
        }

        //    edit prospect model
        function openEditProspectModal(prospect) {
            document.getElementById('edit-company_name').value = prospect.company_name;
            document.getElementById('edit-category').value = prospect.category;
            document.getElementById('edit-contact_person').value = prospect.contact_person;
            document.getElementById('edit-phone_number').value = prospect.phone_number;
            document.getElementById('edit-email').value = prospect.email;
            document.getElementById('edit-address').value = prospect.address;
            document.getElementById('edit-message').value = prospect.message;
            document.getElementById('edit-probability').value = prospect.probability;
            document.getElementById('edit-activities').value = prospect.activities;
            document.getElementById('edit-status').value = prospect.status;

            // Format the inquiry date to 'YYYY-MM-DDTHH:MM' if it's present
            if (prospect.inquirydate) {
                const inquiryDate = new Date(prospect.inquirydate);
                const formattedDate = inquiryDate.toISOString().slice(0, 16); // 'YYYY-MM-DDTHH:MM'
                document.getElementById('edit-inquirydate').value = formattedDate;
            } else {
                document.getElementById('edit-inquirydate').value = '';
            }

            // Set the form action to include the prospect ID
            const form = document.getElementById('edit-prospect-form');
            form.action = "<?php echo e(route('prospects.update', '')); ?>/" + prospect.id;

            document.getElementById('edit-prospect-modal').style.display = 'block';
        }


        function closeEditProspectModal() {
            document.getElementById('edit-prospect-modal').style.display = 'none';
        }



        // to show message Function to show the modal with the full message
        function showMessageModal(message) {
            const modal = document.getElementById('message-modal');
            const messageContent = document.getElementById('full-message-content');
            messageContent.textContent = message;
            modal.style.display = 'flex';
        }

        // Function to close the modal
        function closeMessageModal() {
            const modal = document.getElementById('message-modal');
            modal.style.display = 'none';
        }

        // Add event listeners to all 'See More' buttons
        document.querySelectorAll('.see-more-btn').forEach(button => {
            button.addEventListener('click', function() {
                const fullMessage = this.getAttribute('data-message');
                showMessageModal(fullMessage);
            });
        });


        // to show the details in name
        function showDetailsModal(contact_person, phone, email, address, message) {
            document.getElementById('modal-contact_person').textContent = contact_person;
            document.getElementById('modal-phone').textContent = phone;
            document.getElementById('modal-email').textContent = email;
            document.getElementById('modal-address').textContent = address;
            document.getElementById('modal-message').textContent = message;

            const modal = document.getElementById('details-modal');
            modal.style.display = 'flex';
        }

        // Function to close the details modal
        function closeDetailsModal() {
            const modal = document.getElementById('details-modal');
            modal.style.display = 'none';
        }

        // Add event listeners to all "See Details" buttons
        document.querySelectorAll('.btn-see-details').forEach(button => {
            button.addEventListener('click', function() {
                const contact_person = this.getAttribute('data-contact_person');
                const phone = this.getAttribute('data-phone');
                const email = this.getAttribute('data-email');
                const address = this.getAttribute('data-address');
                const message = this.getAttribute('data-message');
                showDetailsModal(contact_person, phone, email, message);
            });
        });

        // activities model
        function openAddActivityModal(prospectId) {
            document.getElementById('activity-prospect-id').value = prospectId;
            document.getElementById('add-activity-modal').style.display = 'block';
        }

        // close add activity
        function closeAddActivityModal() {
            document.getElementById('add-activity-modal').style.display = 'none';
        }

        // view activities
        function viewActivities(prospectId) {
            // Fetch activities from the server for the given prospect
            fetch(`/prospects/${prospectId}/activities`)
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

        // Function to like an activity
        function likeActivity(activityId) {
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch(`/activities/${activityId}/like`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': token,
                        'Accept': 'text/html'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.text(); // Expecting HTML response
                })
                .then(html => {
                    document.getElementById(`like-count-${activityId}`).innerHTML = html;
                })
                .catch(error => console.error('Error liking activity:', error));
        }

        // reply to activities
        function replyToActivity(activityId) {
            const replyInput = document.getElementById(`reply-input-${activityId}`);
            const reply = replyInput.value;
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch(`/activities/${activityId}/reply`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': token,
                        'Accept': 'text/html',
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: new URLSearchParams({
                        reply: reply
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.text(); // Expecting HTML response
                })
                .then(html => {
                    document.getElementById(`replies-${activityId}`).innerHTML = html;
                    replyInput.value = ''; // Clear the reply input after success
                })
                .catch(error => console.error('Error replying to activity:', error));
        }


        // close view activities
        function closeViewActivitiesModal() {
            document.getElementById('view-activities-modal').style.display = 'none';
        }

        // delete prospects
        function deleteProspect(id) {
            if (confirm('Are you sure you want to delete this prospect?')) {
                // Send a DELETE request to the server
                fetch(`/prospects/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>', // Include CSRF token
                            'Content-Type': 'application/json'
                        },
                    })
                    .then(response => {
                        if (response.ok) {
                            // Remove the row from the table or refresh the list
                            location.reload(); // Reload the page to reflect the changes
                        } else {
                            alert('Error deleting prospect.');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred while deleting the prospect.');
                    });
            }
        }

        // success message 
        document.addEventListener('DOMContentLoaded', function() {
            // Check if the success message exists
            const successMessage = document.getElementById('success-message');
            if (successMessage) {
                setTimeout(() => {
                    successMessage.style.display = 'none'; // Hide the message after 3 seconds
                }, 1500);
            }
        });



        function toggleFilterList() {
            const filterOptions = document.querySelector('.filter-options');
            filterOptions.style.display = filterOptions.style.display === 'none' ? 'block' : 'none';

            // Populate the select options with the current selected values only if the options are shown
            if (filterOptions.style.display === 'block') {
                populateSelectedFilters();
            }
        }

        function populateSelectedFilters() {
            const inquiryDateSelect = document.getElementById('inquiry-date');
            const categorySelect = document.getElementById('category');
            const statusSelect = document.getElementById('status');

            // Get the selected values from the current state
            const selectedInquiryDate = inquiryDateSelect.value;
            const selectedCategory = categorySelect.value;
            const selectedStatus = statusSelect.value;

            // Update the display of the inquiry date select to show the selected value
            if (selectedInquiryDate === 'date-range') {
                document.getElementById('date-range-picker').style.display = 'block';
            } else {
                document.getElementById('date-range-picker').style.display = 'none';
            }

            inquiryDateSelect.value = selectedInquiryDate;
            categorySelect.value = selectedCategory;
            statusSelect.value = selectedStatus;
        }

        // Optional: Close the filter options if clicking outside of them
        document.addEventListener('click', function(event) {
            const filterDiv = document.querySelector('.filter-prospects');
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
            const inquiryDate = document.getElementById('inquiry-date').value;
            const category = document.getElementById('category').value;
            const status = document.getElementById('status').value;
            const fromDate = document.getElementById('from-date').value;
            const toDate = document.getElementById('to-date').value;

            const url = new URL(window.location.href);
            url.searchParams.set('inquiry_date', inquiryDate);
            url.searchParams.set('filter_category', category);
            url.searchParams.set('sort_status', status);

            if (inquiryDate === 'date-range') {
                url.searchParams.set('from_date', fromDate);
                url.searchParams.set('to_date', toDate);
            }

            window.location.href = url.toString();
        }

        function openAddTaskModal(prospectId) {
            // Set the hidden input for prospect ID
            document.getElementById('prospect-id').value = prospectId;
            console.log('Opening modal for prospect ID:', prospectId);
            document.getElementById('add-task-modal').style.display = 'block';
        }

        function closeAddTaskModal() {
            document.getElementById('add-task-modal').style.display = 'none';
        }
    </script>
</main>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('frontends.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\Oct 29- Live edited-project management\resources\views/frontends/prospects.blade.php ENDPATH**/ ?>