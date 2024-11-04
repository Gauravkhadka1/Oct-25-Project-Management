<?php $__env->startSection('main-container'); ?>

<?php if(session('success')): ?>
<div id="success-message" style="position: fixed; top: 20%; left: 50%; transform: translate(-50%, -50%); background-color: #28a745; color: white; padding: 15px; border-radius: 5px; z-index: 9999;">
    <?php echo e(session('success')); ?>

</div>

<?php endif; ?>

<div class="payments-page">
    <div class="payments-heading">
        <h1>Due Payments Data</h1>
    </div>
    <div class="create-payments">
        <button class="btn-create" onclick="openCreatePaymentsModal()"><img src="<?php echo e(url ('/frontend/images/add.png')); ?>" alt=""> Due Payments</button>
    </div>

    <table class="modern-payments-table">
        <thead>
            <tr>
                <th>SN</th>
                <th>
                    Company Name
                    <a href="#" onclick="toggleFilter('company_name_sort')">
                        <img src="<?php echo e(url('/frontend/images/bars-filter.png')); ?>" alt="" class="barfilter">
                    </a>
                    <div id="company_name_sort" class="filter-dropdown" style="display: none;">
                        <form action="<?php echo e(route('payments.index')); ?>" method="GET">
                            <select name="sort_payments_name" onchange="this.form.submit()">
                                <option value="">Sort by Company Name</option>
                                <option value="a_to_z" <?php echo e(request('sort_payments_name') == 'a_to_z' ? 'selected' : ''); ?>>A to Z</option>
                                <option value="z_to_a" <?php echo e(request('sort_payments_name') == 'z_to_a' ? 'selected' : ''); ?>>Z to A</option>
                            </select>
                        </form>
                    </div>
                </th>
               <th>
                    Category
                    <a href="#" onclick="toggleFilter('category-filter')">
                        <img src="/frontend/images/bars-filter.png" alt="" class="barfilter">
                    </a>
                    <div id="category-filter" class="filter-dropdown" style="display: none;">
                        <form action="<?php echo e(route('payments.index')); ?>" method="GET">
                            <select name="filter_category" onchange="this.form.submit()">
                                <option value="">All Category</option> <!-- Change this value to empty string -->
                                <option value="Website" <?php echo e(request('filter_category') == 'Website' ? 'selected' : ''); ?>>Website</option>
                                <option value="Renewal" <?php echo e(request('filter_category') == 'Renewal' ? 'selected' : ''); ?>>Renewal</option>
                            </select>
                        </form>
                    </div>
                </th>
                <th>
                    Amount
                    <a href="#" onclick="toggleFilter('amount_sort')">
                        <img src="/frontend/images/bars-filter.png" alt="" class="barfilter">
                    </a>
                    <div id="amount_sort" class="filter-dropdown" style="display: none;">
                        <form action="<?php echo e(route('payments.index')); ?>" method="GET">
                            <select name="sort_amount" onchange="this.form.submit()">
                                <option value="">Sort by Amount</option>
                                <option value="high_to_low" <?php echo e(request('sort_amount') == 'high_to_low' ? 'selected' : ''); ?>>High to Low</option>
                                <option value="low_to_high" <?php echo e(request('sort_amount') == 'low_to_high' ? 'selected' : ''); ?>>Low to High</option>
                            </select>
                        </form>
                    </div>
                </th>

                <th>Activities</th>
                <th>Edit</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $payments): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($key + 1); ?></td>
                <td class="company-name-td">
                    <span class="payments-name"><?php echo e($payments->company_name); ?></span>
                    <button class="btn-see-details" data-contact_person="<?php echo e($payments->contact_person); ?>" data-phone="<?php echo e($payments->phone); ?>" data-email="<?php echo e($payments->email); ?>">
                        <div class="btn-see-detail-img">
                            <img src="<?php echo e(url ('/frontend/images/info.png')); ?>" alt="">
                        </div>
                    </button>
                </td>
                <td><?php echo e($payments->category); ?></td>
                <td><?php echo e($payments->amount); ?></td>
                <td>
                    <button class="btn-add-activity" onclick="openAddActivityModal(<?php echo e($payments->id); ?>)"><img src="<?php echo e(url ('/frontend/images/plus.png')); ?>" alt=""></button>
                    <button class="btn-view-activities" onclick="viewActivities(<?php echo e($payments->id); ?>)"><img src="<?php echo e(url ('/frontend/images/view.png')); ?>" alt=""></button>
                </td>
                <td class="payment-edit-delete">
                    <button class="btn-create" onclick="openEditPaymentsModal(<?php echo e(json_encode($payments)); ?>)"><img src="<?php echo e(url ('/frontend/images/edit.png')); ?>" alt=""></button>
                    <form action="<?php echo e(route('payments.destroy', $payments->id)); ?>" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this payment?');">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button type="submit" class="btn-cancel"><img src="<?php echo e(url ('/frontend/images/delete.png')); ?>" alt=""></button>
                    </form>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" style="text-align: right; font-weight: bold;"><?php echo e($totalDuesText); ?></td>
                <td colspan="3" style="font-weight: bold;">
                    <?php echo e(number_format($filteredTotalAmount)); ?>

                </td>
            </tr>
        </tfoot>
    </table>


    <!-- See Details Modal -->
    <div id="details-modal" class="details-modal" style="display: none;">
        <div class="details-modal-content">
            <h3>Payments Details</h3>
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

                </select><br>

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

    <!-- Add Activity Modal -->
    <div id="add-activity-modal" class="modal">
        <div class="modal-content">
            <h3>Add Activity</h3>
            <form id="add-activity-form" action="<?php echo e(route('payments-activities.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="payments_id" id="activity-payments-id">

                <label for="activity-details">Activity Details:</label>
                <input type="text" name="details" id="activity-details" required>

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

        // Filter
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

</div>


<?php $__env->stopSection(); ?>
<?php echo $__env->make('frontends.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\Oct 29- Live edited-project management\resources\views/frontends/payments.blade.php ENDPATH**/ ?>