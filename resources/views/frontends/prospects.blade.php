@extends('frontends.layouts.main')

@section('main-container')

<main>
    @if (session('success'))
    <div id="success-message" style="position: fixed; top: 20%; left: 50%; transform: translate(-50%, -50%); background-color: #28a745; color: white; padding: 15px; border-radius: 5px; z-index: 9999;">
        {{ session('success') }}
    </div>

    @endif

   <div class="prospects-list">
        <div class="prospect-heading">
            <div class="prospect-heading">
                <h2>Prospects</h2>
            </div>
            <div class="create-prospect">
                <button class="btn-create" onclick="openCreateProspectModal()">Create Prospect</button>
            </div>
        </div>
        
        <table class="styled-table">
        <thead>
    <tr>
        <th>SN</th>
        <th>
            Name
            <a href="#" onclick="toggleFilter('name-filter')">
                <i class="fas fa-filter"></i> <!-- Filter Icon -->
            </a>
            <div id="name-filter" class="filter-dropdown" style="display: none;">
                <form action="{{ route('prospects.index') }}" method="GET">
                    <select name="sort_name" onchange="this.form.submit()">
                        <option value="">Select</option>
                        <option value="asc" {{ request('sort_name') == 'asc' ? 'selected' : '' }}>A-Z</option>
                        <option value="desc" {{ request('sort_name') == 'desc' ? 'selected' : '' }}>Z-A</option>
                    </select>
                </form>
            </div>
        </th>
        <th>
            Category
            <a href="#" onclick="toggleFilter('category-filter')">
                <i class="fas fa-filter"></i> <!-- Filter Icon -->
            </a>
            <div id="category-filter" class="filter-dropdown" style="display: none;">
                <form action="{{ route('prospects.index') }}" method="GET">
                    <select name="filter_category" onchange="this.form.submit()">
                        <option value="">All Categories</option>
                        <option value="Ecommerce" {{ request('filter_category') == 'Ecommerce' ? 'selected' : '' }}>Ecommerce</option>
                        <option value="NGO/ INGO" {{ request('filter_category') == 'NGO/ INGO' ? 'selected' : '' }}>NGO/ INGO</option>
                        <option value="Tourism" {{ request('filter_category') == 'Tourism' ? 'selected' : '' }}>Tourism</option>
                        <option value="Education" {{ request('filter_category') == 'Education' ? 'selected' : '' }}>Education</option>
                        <option value="Other" {{ request('filter_category') == 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                </form>
            </div>
        </th>
        <th>
            Inquiry Date
            <a href="#" onclick="toggleFilter('inquirydate-filter')">
                <i class="fas fa-filter"></i> <!-- Filter Icon -->
            </a>
            <div id="inquirydate-filter" class="filter-dropdown" style="display: none;">
                <form action="{{ route('prospects.index') }}" method="GET">
                    <select name="sort_inquirydate" onchange="this.form.submit()">
                        <option value="">Select</option>
                        <option value="desc" {{ request('sort_inquirydate') == 'desc' ? 'selected' : '' }}>Most Recent</option>
                        <option value="asc" {{ request('sort_inquirydate') == 'asc' ? 'selected' : '' }}>Oldest</option>
                    </select>
                </form>
            </div>
        </th>
        <th>
            Probability
            <a href="#" onclick="toggleFilter('probability-filter')">
                <i class="fas fa-filter"></i> <!-- Filter Icon -->
            </a>
            <div id="probability-filter" class="filter-dropdown" style="display: none;">
                <form action="{{ route('prospects.index') }}" method="GET">
                    <select name="sort_probability" onchange="this.form.submit()">
                        <option value="">Select</option>
                        <option value="desc" {{ request('sort_probability') == 'desc' ? 'selected' : '' }}>Higher to Lower</option>
                        <option value="asc" {{ request('sort_probability') == 'asc' ? 'selected' : '' }}>Lower to Higher</option>
                    </select>
                </form>
            </div>
        </th>
        <th>Activities</th>
        <th>
            Status
            <a href="#" onclick="toggleFilter('status-filter')">
                <i class="fas fa-filter"></i> <!-- Filter Icon -->
            </a>
            <div id="status-filter" class="filter-dropdown" style="display: none;">
                <form action="{{ route('prospects.index') }}" method="GET">
                    <select name="sort_status" onchange="this.form.submit()">
                        <option value="">All Statuses</option>
                        <option value="Not Responded" {{ request('sort_status') == 'Not Responded' ? 'selected' : '' }}>Not Responded</option>
                        <option value="Dealing" {{ request('sort_status') == 'Dealing' ? 'selected' : '' }}>Dealing</option>
                        <option value="Converted" {{ request('sort_status') == 'Converted' ? 'selected' : '' }}>Converted</option>
                        <option value="Missed" {{ request('sort_status') == 'Missed' ? 'selected' : '' }}>Missed</option>
                    </select>
                </form>
            </div>
        </th>
        <th>Edit</th>
    </tr>
</thead>

            <tbody>
            @foreach ($prospects as $key => $prospect)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>
                        <span class="prospect-name">{{ $prospect->name }}</span>
                        <button class="btn-see-details" data-phone="{{ $prospect->phone_number }}" data-email="{{ $prospect->email }}" data-message="{{ $prospect->message }}">
                            See Details
                        </button>
                    </td>


                    <td>{{ $prospect->category }}</td>

                    <td>{{ $prospect->inquirydate ? $prospect->inquirydate->format('Y-m-d h:i A') : 'N/A' }}</td>
                    <td>{{ $prospect->probability }}%</td>
                    <td>
                        <button class="btn-add-activity" onclick="openAddActivityModal({{ $prospect->id }})">Add Activity</button>
                        <button class="btn-view-activities" onclick="viewActivities({{ $prospect->id }})">View Activities</button>
                    </td>


                    <td>{{ $prospect->status }}</td>
                    <td>
                        <button class="btn-create" onclick="openEditProspectModal({{ json_encode($prospect) }})">Edit</button>
                        <form action="{{ route('prospects.destroy', $prospect->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this prospect?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-cancel">Delete</button>
                        </form>
                    </td>

                </tr>
            @endforeach                 
            </tbody>
        </table>
      
   </div>


   <!-- details modals -->
    <!-- See Details Modal -->
        <div id="details-modal" class="details-modal" style="display: none;">
        <div class="details-modal-content">
            <h3>Prospect Details</h3>
            <p><strong>Phone:</strong> <span id="modal-phone"></span></p>
            <p><strong>Email:</strong> <span id="modal-email"></span></p>
            <p><strong>Message:</strong> <span id="modal-message"></span></p>
            <div class="details-modal-buttons">
                <button type="button" class="btn-cancel" onclick="closeDetailsModal()">Close</button>
            </div>
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
            <form action="{{ route('prospects.store') }}" method="POST">
                @csrf
                <label for="prospect-name">Prospect Name:</label>
                <input type="text" name="name" id="prospect-name" required><br>

                <label for="category">Category</label>
                <select name="category" id="category">
                    <option value="Ecommerce">Ecommerce</option>
                    <option value="NGO/ INGO">NGO/ INGO</option>
                    <option value="Tourism">Tourism</option>
                    <option value="Education">Education</option>
                    <option value="Other">Other</option>
                </select><br>

                <label for="phone_number">Phone Number:</label>
                <input type="text" name="phone_number" id="phone_number"><br>

                <label for="email">Email:</label>
                <input type="email" name="email" id="email"><br>

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
            <form id="edit-prospect-form" action="{{ route('prospects.update', '') }}" method="POST">
                @csrf
                @method('PUT')
                <label for="edit-prospect-name">Prospect Name:</label>
                <input type="text" name="name" id="edit-prospect-name"><br>

                <label for="edit-category">Category</label>
                <select name="category" id="edit-category">
                    <option value="Ecommerce">Ecommerce</option>
                    <option value="NGO/ INGO">NGO/ INGO</option>
                    <option value="Tourism">Tourism</option>
                    <option value="Education">Education</option>
                </select><br>

                <label for="edit-phone_number">Phone Number:</label>
                <input type="text" name="phone_number" id="edit-phone_number"><br>

                <label for="edit-email">Email:</label>
                <input type="email" name="email" id="edit-email"><br>

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
        <form id="add-activity-form" action="{{ route('activities.store') }}" method="POST">
            @csrf
            <input type="hidden" name="prospect_id" id="activity-prospect-id">
            
            <label for="activity-details">Activity Details:</label>
            <input type="text" name="details" id="activity-details" required><br>

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
       function openCreateProspectModal() {
           document.getElementById('create-prospect-modal').style.display = 'block';
       }

       function closeCreateProspectModal() {
           document.getElementById('create-prospect-modal').style.display = 'none';
       }

    //    edit prospect model

       function openEditProspectModal(prospect) {
            document.getElementById('edit-prospect-name').value = prospect.name;
            document.getElementById('edit-category').value = prospect.category;
            document.getElementById('edit-phone_number').value = prospect.phone_number;
            document.getElementById('edit-email').value = prospect.email;
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
            form.action = "{{ route('prospects.update', '') }}/" + prospect.id;

            document.getElementById('edit-prospect-modal').style.display = 'block';
        }


       function closeEditProspectModal() {
           document.getElementById('edit-prospect-modal').style.display = 'none';
       }



    //    For filter

    function toggleFilter(id) {
        // Close other filters first
        document.querySelectorAll('.filter-dropdown').forEach(dropdown => {
            if (dropdown.id !== id) {
                dropdown.style.display = 'none';
            }
        });

        // Toggle the selected filter dropdown
        var element = document.getElementById(id);
        if (element.style.display === 'none' || element.style.display === '') {
            element.style.display = 'block';
        } else {
            element.style.display = 'none';
        }
    }

    // Close the dropdowns if clicked outside, but ignore clicks inside the dropdown
    window.onclick = function(event) {
        // Check if the click is inside the dropdown or on the filter icon
        if (!event.target.closest('.filter-dropdown') && !event.target.matches('.fas')) {
            document.querySelectorAll('.filter-dropdown').forEach(dropdown => {
                dropdown.style.display = 'none';
            });
        }
    }


    // to show message
    // Function to show the modal with the full message
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
            button.addEventListener('click', function () {
                const fullMessage = this.getAttribute('data-message');
                showMessageModal(fullMessage);
            });
        });


        // to show the details in name
        // Function to show the details modal
        function showDetailsModal(phone, email, message) {
            document.getElementById('modal-phone').textContent = phone;
            document.getElementById('modal-email').textContent = email;
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
            button.addEventListener('click', function () {
                const phone = this.getAttribute('data-phone');
                const email = this.getAttribute('data-email');
                const message = this.getAttribute('data-message');
                showDetailsModal(phone, email, message);
            });
        });


        // activities model

        function openAddActivityModal(prospectId) {
            document.getElementById('activity-prospect-id').value = prospectId;
            document.getElementById('add-activity-modal').style.display = 'block';
        }

        function closeAddActivityModal() {
            document.getElementById('add-activity-modal').style.display = 'none';
        }

       function viewActivities(prospectId) {
    // Fetch activities from the server for the given prospect
    fetch(`/prospects/${prospectId}/activities`)
        .then(response => response.json())
        .then(data => {
            const activitiesList = document.getElementById('activities-list');
            activitiesList.innerHTML = ''; // Clear previous activities

            data.activities.forEach(activity => {
                const utcDate = new Date(activity.created_at); // Get the date in UTC

                // JavaScript automatically handles local time conversion based on the browser's timezone
                const localTime = utcDate;

                // Debugging logs to ensure correct times
                console.log('UTC Time:', utcDate);
                console.log('Local Time:', localTime);

                // Formatting options for day, date, and time
                const dateOptions = { 
                    weekday: 'long',  // Add this to show the day of the week
                    year: 'numeric', 
                    month: '2-digit', 
                    day: '2-digit' 
                };
                const timeOptions = { 
                    hour: '2-digit', 
                    minute: '2-digit', 
                    hour12: true 
                };

                const formattedDate = localTime.toLocaleDateString('en-NP', dateOptions); // Format date to include day
                const formattedTime = localTime.toLocaleTimeString('en-US', timeOptions).replace(/^0/, ''); // Format time

                // More debugging logs for formatted date and time
                console.log('Formatted Date:', formattedDate);
                console.log('Formatted Time:', formattedTime);

                // Create a card-like structure for each activity, retaining your original format
                const activityCard = document.createElement('div');
                activityCard.className = 'activity-card'; // Add a class for styling
                activityCard.innerHTML = `
                    <p> ${formattedDate} ${formattedTime}</p>
                    <p><strong>${activity.username}</strong>:  ${activity.details}</p>
                    <hr> <!-- Optional: Add a separator line -->
                `;
                activitiesList.appendChild(activityCard); // Append the card to the list
            });

            // Display the modal
            document.getElementById('view-activities-modal').style.display = 'block';
        });
}


        function closeViewActivitiesModal() {
            document.getElementById('view-activities-modal').style.display = 'none';
        }

        function deleteProspect(id) {
    if (confirm('Are you sure you want to delete this prospect?')) {
        // Send a DELETE request to the server
        fetch(`/prospects/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}', // Include CSRF token
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
        document.addEventListener('DOMContentLoaded', function () {
            // Check if the success message exists
            const successMessage = document.getElementById('success-message');
            if (successMessage) {
                setTimeout(() => {
                    successMessage.style.display = 'none'; // Hide the message after 3 seconds
                }, 1500);
            }
        });



   </script>   
</main> 

@endsection
