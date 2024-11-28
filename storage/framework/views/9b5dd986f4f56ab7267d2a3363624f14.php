<?php $__env->startSection('main-container'); ?>

<?php if(session('success')): ?>
<div id="success-message" style="position: fixed; top: 20%; left: 50%; transform: translate(-50%, -50%); background-color: #28a745; color: white; padding: 15px; border-radius: 5px; z-index: 9999;">
    <?php echo e(session('success')); ?>

</div>

<?php endif; ?>

<div id="success-message" style="display: none; position: fixed; top: 20%; left: 50%; transform: translate(-50%, -50%); background-color: #28a745; color: white; padding: 15px; border-radius: 5px; z-index: 9999;">
    <!-- Success message will be dynamically inserted here -->
</div>

<div class="payments-page">
    <div class="payments-heading">
        <div class="payments-heading-h2">
        <h1>Due Payments</h1>
        </div>
        <div class="create-filter-search">
            <div class="create-payments">
                <button class="btn-create" onclick="openCreatePaymentsModal()"><img src="<?php echo e(url ('/frontend/images/add-new.png')); ?>" alt=""></button>
            </div>
            <div class="filter-section">
                <div class="filter-payments" onclick="toggleFilterList()">
                <img src="<?php echo e(asset('frontend/images/bars-filter.png')); ?>" alt="" class="barfilter">

                    <div class="filter-count">
                        <?php if($filterCount > 0): ?>
                        <p><?php echo e($filterCount); ?></p>
                        <?php endif; ?>
                    </div>
                    Filter
                </div>
                <div class="filter-options" style="display: none;">
                    <form action="<?php echo e(route('payments.index')); ?>" method="GET">
                        <!-- Category Filter -->
                        <div class="filter-item">
                            <label for="category">Category:</label>
                            <select id="category" name="filter_category" class="filter-select">
                                <option value="">Select Options</option>
                                <option value="website" <?php echo e(request('filter_category') == 'website' ? 'selected' : ''); ?>>Website</option>
                                <option value="renewal" <?php echo e(request('filter_category') == 'renewal' ? 'selected' : ''); ?>>Renewal</option>
                                <option value="other" <?php echo e(request('filter_category') == 'other' ? 'selected' : ''); ?>>Other</option>
                            </select>
                        </div>

                        <!-- Amount Filter -->
                        <div class="filter-item">
                            <label for="amount">Amount:</label>
                            <select id="amount" name="amount" class="filter-select" onchange="handleDateRange(this)">
                                <option value="">Select Options</option>
                                <option value="high-to-low" <?php echo e(request('amount') == 'high-to-low' ? 'selected' : ''); ?>>High to low</option>
                                <option value="low-to-high" <?php echo e(request('amount') == 'low-to-high' ? 'selected' : ''); ?>>Low to High</option>
                            </select>
                        </div>

                        <!-- Due Days Filters -->
                        <div class="filter-item">
                            <label for="due_date">Days Remaining:</label>
                            <select id="due_date" name="due_date" class="filter-select">
                                <option value="">Select Options</option>
                                <option value="less-days" <?php echo e(request('due_date') == 'less-days' ? 'selected' : ''); ?>>Less Days</option>
                                <option value="more-days" <?php echo e(request('due_date') == 'more-days' ? 'selected' : ''); ?>>More Days</option>
                            </select>
                        </div>



                        <button type="submit">Apply Filter</button>
                    </form>
                </div>

            </div>
            <div class="search-payments">
    <div class="search-icon">
        <img src="frontend/images/search-icon.png" alt="" class="searchi-icon">
    </div>
    <form action="<?php echo e(route('payments.index')); ?>" method="GET" id="search-form">
        <div class="search-text-area">
            <input type="text" id="search-input" name="search" placeholder="Search payments" value="<?php echo e(request('search')); ?>">
        </div>
    </form>
</div>
        </div>
    </div>



    <div class="task-board">
        <!-- Column for To Do tasks -->
        <div class="task-column" id="due" data-status="due">
            <div class="todo-heading-payments">
                <img src="<?php echo e(url ('frontend/images/due.png')); ?>" alt="">
                <h3>Dues</h3>
            </div>

            <div class="task-list">
                <?php $__currentLoopData = $payments->filter(fn($payment) => $payment->status === 'due' || is_null($payment->status)); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                <div class="task" draggable="true" data-task-id="<?php echo e($payment->id); ?>" data-task-type="<?php echo e(strtolower($payment->category)); ?>">
                    <div class="task-name">
                        <a href="<?php echo e(url ('paymentdetails/' .$payment->id)); ?>">
                            <p><?php echo e($payment->company_name); ?></p>
                        </a>

                    </div>
                    <div class="category">
                        <img src="<?php echo e(url ('frontend/images/category.png')); ?>" alt=""> : <?php echo e($payment->category); ?>

                    </div>

                    <div class="inquiry-date">
                        NPR: <?php echo e($payment->amount); ?>

                    </div>
                    <div class="probability">
                        <?php if(is_null($payment->due_days)): ?>
                        N/A
                        <?php elseif($payment->due_days < 0): ?>
                            Due in <?php echo e(abs($payment->due_days)); ?> day's
                            <?php elseif($payment->due_days > 0): ?>
                            Overdue by <?php echo e($payment->due_days); ?> day's
                            <?php else: ?>
                            Due today
                            <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>


        <!-- Column for In Progress tasks -->
        <div class="task-column" id="invoice_sent" data-status="invoice_sent">
            <div class="invoicesent-heading">
                <img src="<?php echo e(url ('frontend/images/sentsent.png')); ?>" alt="">
                <h3>Payment Details Sent</h3>
            </div>

            <div class="task-list">
                <?php $__currentLoopData = $payments->where('status', 'invoice_sent'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="task" draggable="true" data-task-id="<?php echo e($payment->id); ?>" data-task-type="<?php echo e(strtolower($payment->category)); ?>">
                    <div class="task-name">
                    <a href="<?php echo e(url ('paymentdetails/' .$payment->id)); ?>">
                            <p><?php echo e($payment->company_name); ?></p>
                        </a>
                    </div>
                    <div class="category">
                        <img src="<?php echo e(url ('frontend/images/category.png')); ?>" alt=""> : <?php echo e($payment->category); ?>

                    </div>

                    <div class="inquiry-date">
                        NPR: <?php echo e($payment->amount); ?>

                    </div>
                    <div class="probability">
                        <?php if(is_null($payment->due_days)): ?>
                        N/A
                        <?php elseif($payment->due_days < 0): ?>
                            Due in <?php echo e(abs($payment->due_days)); ?> day's
                            <?php elseif($payment->due_days > 0): ?>
                            Overdue by <?php echo e($payment->due_days); ?> day's
                            <?php else: ?>
                            Due today
                            <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        <!-- Column for QA tasks -->
        <div class="task-column" id="vatbill_sent" data-status="vatbill_sent">
            <div class="vatbillsent-heading">
                <img src="<?php echo e(url ('frontend/images/sentsent.png')); ?>" alt="">
                <h3>Vat Bill Sent</h3>
            </div>
            <div class="task-list">
                <?php $__currentLoopData = $payments->where('status', 'vatbill_sent'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="task" draggable="true" data-task-id="<?php echo e($payment->id); ?>" data-task-type="<?php echo e(strtolower($payment->category)); ?>">
                    <div class="task-name">
                    <a href="<?php echo e(url ('paymentdetails/' .$payment->id)); ?>">
                            <p><?php echo e($payment->company_name); ?></p>
                        </a>
                    </div>
                    <div class="category">
                        <img src="<?php echo e(url ('frontend/images/category.png')); ?>" alt=""> : <?php echo e($payment->category); ?>

                    </div>

                    <div class="inquiry-date">
                        NPR: <?php echo e($payment->amount); ?>

                    </div>
                    <div class="probability">
                        <?php if(is_null($payment->due_days)): ?>
                        N/A
                        <?php elseif($payment->due_days < 0): ?>
                            Due in <?php echo e(abs($payment->due_days)); ?> day's
                            <?php elseif($payment->due_days > 0): ?>
                            Overdue by <?php echo e($payment->due_days); ?> day's
                            <?php else: ?>
                            Due today
                            <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        <!-- Column for Completed tasks -->
        <div class="task-column" id="paid" data-status="paid">
            <div class="paid-heading">
                <img src="<?php echo e(url ('frontend/images/sentsent.png')); ?>" alt="">
                <h3>Paid</h3>
            </div>
            <div class="task-list">
                <?php $__currentLoopData = $payments->where('status', 'paid'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="task" draggable="true" data-task-id="<?php echo e($payment->id); ?>" data-task-type="<?php echo e(strtolower($payment->category)); ?>">
                    <div class="task-name">
                    <a href="<?php echo e(url ('paymentdetails/' .$payment->id)); ?>">
                            <p><?php echo e($payment->company_name); ?></p>
                        </a>
                    </div>
                    <div class="category">
                        <img src="<?php echo e(url ('frontend/images/category.png')); ?>" alt=""> : <?php echo e($payment->category); ?>

                    </div>

                    <div class="inquiry-date">
                        NPR: <?php echo e($payment->amount); ?>

                    </div>
                    <div class="probability">
                        <?php if(is_null($payment->due_days)): ?>
                        N/A
                        <?php elseif($payment->due_days < 0): ?>
                            Due in <?php echo e(abs($payment->due_days)); ?> day's
                            <?php elseif($payment->due_days > 0): ?>
                            Overdue by <?php echo e($payment->due_days); ?> day's
                            <?php else: ?>
                            Due today
                            <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>
    <tfoot>
        <tr>
            <td colspan="3" style="text-align: right; font-weight: bold;"><?php echo e($totalDuesText); ?></td>
            <td colspan="3" style="font-weight: bold;">
                <?php echo e(number_format($filteredTotalAmount)); ?>

            </td>
        </tr>
    </tfoot>

    <!-- See Details Modal -->
    <div id="details-modal" class="details-modal" style="display: none;">
        <div class="details-modal-content">
            <h3>Contact Details</h3>
            <p><strong>Contact person:</strong> <span id="modal-contact_person"></span></p>
            <p><strong>Phone:</strong> <span id="modal-phone"></span></p>
            <p><strong>Email:</strong> <span id="modal-email"></span></p>
            <div class="details-modal-buttons">
                <button type="button" class="btn-cancel" onclick="closeDetailsModal()">Close</button>
            </div>
        </div>
    </div>
    <!------- Create Payments Data ------>
    <div id="create-payments-modal" class="modal" style="display: none;">
        <div class="modal-content">
            <h3>Create New Payments</h3>
            <form action="<?php echo e(route('payments.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <label for="prospect-company_name">Company Name:</label>
                <input type="text" name="company_name" id="payments-company_name" required><br>

                <label for="prospect-contact_person">Contact Person:</label>
                <input type="text" name="contact_person" id="payments-contact_person"><br>

                <label for="phone_number">Phone Number:</label>
                <input type="text" name="phone" id="phone"><br>

                <label for="email">Email:</label>
                <input type="email" name="email" id="email"><br>

                <label for="category">Category</label>
                <select name="category" id="category">
                    <option value="Website">Website</option>
                    <option value="Microsoft">Microsoft</option>
                    <option value="Renewal">Renewal</option>
                    <option value="Other">Other</option>
                </select><br>

                <label for="amount">Amount:</label>
                <input type="number" name="amount" id="amount"><br>

                <label for="due_date">Due Date</label>
                <input type="date" name="due_date" id="due_date"><br>

                <div class="modal-buttons">
                    <button type="submit" class="btn-submit">Add Payments</button>
                    <button type="button" class="btn-cancel" onclick="closeCreatePaymentsModal()">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <!-- edit payments data -->
    <div id="edit-payments-modal" class="modal" style="display: none;">
        <div class="modal-content">
            <h3>Edit Payments</h3>
            <form id="edit-payments-form" action="<?php echo e(route('payments.update', '')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                <label for="edit-prospect-company_name">Company Name:</label>
                <input type="text" name="company_name" id="edit-company_name"><br>

                <label for="edit-contact_person">Contact Person:</label>
                <input type="text" name="contact_person" id="edit-contact_person"><br>

                <label for="edit-phone">Phone Number:</label>
                <input type="text" name="phone" id="edit-phone"><br>

                <label for="edit-email">Email:</label>
                <input type="email" name="email" id="edit-email"><br>

                <label for="category">Category</label>
                <select name="category" id="edit-category">
                    <option value="Website">Website</option>
                    <option value="Microsoft">Microsoft</option>
                    <option value="Renewal">Renewal</option>
                    <option value="Other">Other</option>
                </select><br>

                <label for="amount">Amount:</label>
                <input type="number" name="amount" id="edit-amount"><br>

                <div class="modal-buttons">
                    <button type="submit" class="btn-submit">Update Payments</button>
                    <button type="button" class="btn-cancel" onclick="closeEditPaymentsModal()">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal for Adding New Task -->
    <div id="add-task-modal" class="custom-modal" style="display: none;">
        <div class="custom-modal-content">
            <span class="custom-close" onclick="closeAddTaskModal()">&times;</span>
            <h3 class="custom-modal-title">Add New Task</h3>
            <form action="<?php echo e(route('paymentstasks.store')); ?>" method="POST" class="custom-form">
                <?php echo csrf_field(); ?>
                <input type="hidden" id="payment-id" name="payments_id" value="">

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


    <!-- View Activities Modal -->
    <div id="view-activities-modal" class="modal">
        <div class="modal-content">
            <h3>Activities</h3>
            <div id="activities-list">
                <!-- Activities will be populated here -->
            </div>

            <!-- Sticky Add Activity Section -->
            <div id="add-activity-section" class="sticky-section">
                <form id="add-activity-form" action="<?php echo e(route('payments-activities.store')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="payments_id" id="activity-payments-id">
                    <input type="text" name="details" id="activity-details" placeholder="Add Comments..." required>
                    <div id="suggestions"></div>
                    <div class="form-buttons">
                        <button type="submit" class="btn-submit">Add<div id="loading-spinner" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 9999;">
                                <img src="<?php echo e(url('frontend/images/spinner.gif')); ?>" alt="Loading...">
                            </div>
                        </button>
                    </div>
                </form>
            </div>

            <div class="modal-buttons">
                <button type="button" class="btn-cancel" onclick="closeViewActivitiesModal()">Close</button>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        // open create payments
        function openCreatePaymentsModal() {
            document.getElementById('create-payments-modal').style.display = 'block';
        }

        // close create payments
        function closeCreatePaymentsModal() {
            document.getElementById('create-payments-modal').style.display = 'none';
        }

        //    open edit payments model
        function openEditPaymentsModal(payments) {
            document.getElementById('edit-company_name').value = payments.company_name;
            document.getElementById('edit-contact_person').value = payments.contact_person;
            document.getElementById('edit-phone').value = payments.phone;
            document.getElementById('edit-email').value = payments.email;
            document.getElementById('edit-category').value = payments.category;
            document.getElementById('edit-amount').value = payments.amount;

            // Set the form action to include the prospect ID
            const form = document.getElementById('edit-payments-form');
            form.action = "<?php echo e(route('payments.update', '')); ?>/" + payments.id;

            document.getElementById('edit-payments-modal').style.display = 'block';
        }

        // close edit payments model
        function closeEditPaymentsModal() {
            document.getElementById('edit-payments-modal').style.display = 'none';
        }

        // to show the details in name
        function showDetailsModal(contact_person, phone, email) {
            console.log("Phone:", phone);
            document.getElementById('modal-contact_person').textContent = contact_person;
            document.getElementById('modal-phone').textContent = phone;
            document.getElementById('modal-email').textContent = email;

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
                showDetailsModal(contact_person, phone, email);
            });
        });



        function viewActivities(paymentsId) {
            // Set the hidden input for payments_id in the Add Activity form
            document.getElementById('activity-payments-id').value = paymentsId;

            // Fetch activities and populate them
            fetch(`/payments/${paymentsId}/activities`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    const activitiesList = document.getElementById('activities-list');
                    activitiesList.innerHTML = ''; // Clear previous activities

                    if (data.activities && data.activities.length > 0) {
                        data.activities.reverse().forEach(activity => {
                            const utcDate = new Date(activity.created_at); // Parse the UTC timestamp
                            const localTime = utcDate.toLocaleString('en-US', {
                                weekday: 'long', // Show the day of the week
                                year: 'numeric',
                                month: '2-digit',
                                day: '2-digit',
                                hour: '2-digit',
                                minute: '2-digit',
                                second: undefined, // Omit seconds if not needed
                                hour12: true, // Display in 12-hour format
                            });

                            // Create a card-like structure for each activity
                            const activityCard = document.createElement('div');
                            activityCard.className = 'activity-card';
                            activityCard.innerHTML = `
                        <p>${localTime}</p>
                        <p><strong>${activity.username}</strong>: ${activity.details}</p>
                    `;
                            activitiesList.appendChild(activityCard); // Append the card to the list
                        });
                    } else {
                        activitiesList.innerHTML = '<p>No activities found.</p>'; // Message if no activities
                    }

                    // Display the modal
                    // Display the modal
                    const modal = document.getElementById('view-activities-modal');
                    modal.style.display = 'block';

                    // Add event listener to close modal when clicking outside
                    modal.addEventListener('click', (event) => {
                        if (event.target === modal) {
                            closeViewActivitiesModal();
                        }
                    });
                })
                .catch(error => console.error('Error fetching activities:', error));
        }

        function closeViewActivitiesModal() {
            document.getElementById('view-activities-modal').style.display = 'none';
        }


        // delete payments data
        function deletePayments(id) {
            if (confirm('Are you sure you want to delete this payments?')) {
                // Send a DELETE request to the server
                fetch(`/payments/${id}`, {
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
                            alert('Error deleting payments.');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred while deleting the payments.');
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
            const categorySelect = document.getElementById('category');
            const amountSelect = document.getElementById('amount');

            const urlParams = new URLSearchParams(window.location.search);
            categorySelect.value = urlParams.get('filter_category') || '';
            amountSelect.value = urlParams.get('amount') || '';
        }

        // Optional: Close the filter options if clicking outside of them
        document.addEventListener('click', function(event) {
            const filterDiv = document.querySelector('.filter-payments');
            const filterOptions = document.querySelector('.filter-options');
            if (!filterDiv.contains(event.target) && !filterOptions.contains(event.target)) {
                filterOptions.style.display = 'none';
            }
        });

        function applyFilter() {
            const category = document.getElementById('category').value;
            const amount = document.getElementById('amount').value;

            const url = new URL(window.location.href);
            url.searchParams.set('filter_category', category);
            url.searchParams.set('amount', amount);


            window.location.href = url.toString();
        }

        function openAddTaskModal(paymentId) {
            // Set the hidden input for prospect ID
            document.getElementById('payment-id').value = paymentId;
            console.log('Opening modal for payments ID:', paymentId);
            document.getElementById('add-task-modal').style.display = 'block';
        }

        function closeAddTaskModal() {
            document.getElementById('add-task-modal').style.display = 'none';
        }



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
                fetch("<?php echo e(route('payments.updateStatus')); ?>", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': "<?php echo e(csrf_token()); ?>"
                        },
                        body: JSON.stringify({
                            taskId,
                            status: newStatus
                        })
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
                                    $('#suggestions').append('<div data-username="' + user.username + '">' + user.username + '</div>');
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
                                        $('#suggestions').append('<div data-username="' + user.username + '">' + user.username + '</div>');
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

                const message = $('#activity-details').val();
                const mentionedUser = extractMentionedUser(message); // Extract mentioned user
                const paymentsId = $('#activity-payments-id').val();

                // Show loading spinner
                $('#loading-spinner').show();

                $.ajax({
                    url: $(this).attr('action'), // Use form's action URL
                    method: 'POST',
                    data: {
                        message: message,
                        mentioned_user: mentionedUser, // Include mentioned user
                        payments_id: paymentsId, // Payment ID
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
        let debounceTimeout;

    document.getElementById('search-input').addEventListener('input', function () {
        clearTimeout(debounceTimeout); // Clear the previous timeout
        debounceTimeout = setTimeout(() => {
            document.getElementById('search-form').submit(); // Submit the form after 3 seconds
        }, 1000); // 3000ms = 3 seconds
    });
    </script>

</div>


<?php $__env->stopSection(); ?>
<?php echo $__env->make('frontends.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\Oct 29- Live edited-project management\resources\views/frontends/payments.blade.php ENDPATH**/ ?>