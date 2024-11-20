@extends('frontends.layouts.main')

@section('main-container')
@if (session('success'))
<div id="success-message" style="position: fixed; top: 20%; left: 50%; transform: translate(-50%, -50%); background-color: #28a745; color: white; padding: 15px; border-radius: 5px; z-index: 9999;">
    {{ session('success') }}
</div>

@endif

<div id="success-message" style="display: none; position: fixed; top: 20%; left: 50%; transform: translate(-50%, -50%); background-color: #28a745; color: white; padding: 15px; border-radius: 5px; z-index: 9999;">
    <!-- Success message will be dynamically inserted here -->
</div>

<div class="payment-detail-page">
    <div class="payment-detail">
        <div class="company-name-pd">
            <p>{{ $payment->company_name }}</p>
            <div class="payment-details-due-date">
                <p>Due date:</p> 
                            @if (is_null($payment->due_days))
                                N/A
                            @elseif ($payment->due_days < 0)
                                Due in {{ abs($payment->due_days) }} day's
                            @elseif ($payment->due_days > 0)
                                Overdue by {{ $payment->due_days }} day's
                            @else
                                Due today
                            @endif
                        </div>
        </div>

        <div class="add-payment-task">
            <div class="payment-contact-details">
                <h2>Contact Details</h2>
                <p> <strong>Contact Person:</strong>  {{ $payment->contact_person }}</p>
                <p><strong>C. Person Phone:</strong> {{ $payment->phone }}</p>
                <p><strong>C. Person Email:</strong> {{ $payment->email }}</p>
            </div>
            <button class="btn-create" id="task-create" onclick="openAddTaskModal({{ $payment->id }})">
                <h2>Add Task</h2>
            </button>
        </div>
    </div>

      <!-- Modal for Adding New Task -->
      <div id="add-task-modal" class="custom-modal" style="display: none;">
        <div class="custom-modal-content">
            <span class="custom-close" onclick="closeAddTaskModal()">&times;</span>
            <h3 class="custom-modal-title">Add New Task</h3>
            <form action="{{ route('paymentstasks.store') }}" method="POST" class="custom-form">
                @csrf
                <input type="hidden" id="payment-id" name="payments_id" value="">

                <label for="task-name" class="custom-label">Task Name:</label>
                <input type="text" name="name" id="task-name" class="custom-input" required>

                <label for="assigned-to" class="custom-label">Assigned To:</label>
                <select name="assigned_to" id="assigned-to" class="custom-select" required>
                    <option value="">Select User</option>
                    @foreach ($users as $user)
                    <option value="{{ $user->email }}">{{ $user->username }}</option>
                    @endforeach
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

    <div class="payment-comments">
    <h2>Comments</h2>
    <div id="activities-list">
        <!-- Activities will be dynamically loaded here -->
    </div>

    <!-- Sticky Add Activity Section -->
    <div id="add-activity-section" class="sticky-section">
        <form id="add-activity-form" action="{{ route('payments-activities.store') }}" method="POST">
            @csrf
            <input type="hidden" name="payments_id" id="activity-payments-id" value="{{ $payment->id }}">
            <input type="text" name="details" id="activity-details" placeholder="Add Comments..." required>
            <div id="suggestions"></div>
            <div class="form-buttons">
                <button type="submit" class="btn-submit">Add
                    <div id="loading-spinner" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 9999;">
                        <img src="{{ url('frontend/images/spinner.gif') }}" alt="Loading...">
                    </div>
                </button>
            </div>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        
        function openAddTaskModal(paymentId) {
            // Set the hidden input for payment ID
            document.getElementById('payment-id').value = paymentId;
            console.log('Opening modal for payment ID:', paymentId);

            // Show the modal
            document.getElementById('add-task-modal').style.display = 'flex';
        }

        function closeAddTaskModal() {
            // Hide the modal
            document.getElementById('add-task-modal').style.display = 'none';
        }

        document.addEventListener('DOMContentLoaded', () => {
    const paymentsId = document.getElementById('activity-payments-id').value;

    // Fetch and display activities
    fetch(`/payments/${paymentsId}/activities`)
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
                data.activities.reverse().forEach(activity => {
                    const utcDate = new Date(activity.created_at);
                    const localTime = utcDate.toLocaleString('en-US', {
                        weekday: 'long',
                        year: 'numeric',
                        month: '2-digit',
                        day: '2-digit',
                        hour: '2-digit',
                        minute: '2-digit',
                        hour12: true
                    });

                    // Create a card for each activity
                    const activityCard = document.createElement('div');
                    activityCard.className = 'activity-card';
                    activityCard.innerHTML = `
                        <p>${localTime}</p>
                        <p><strong>${activity.username}</strong>: ${activity.details}</p>
                    `;
                    activitiesList.appendChild(activityCard);
                });
            } else {
                activitiesList.innerHTML = '<p>No activities found.</p>';
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
                data: { query: '' }, // Empty query fetches all usernames
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
                    data: { query: query },
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

    </script>
    @endsection