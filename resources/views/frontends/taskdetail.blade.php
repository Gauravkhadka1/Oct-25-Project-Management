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
<div class="project-task-detail-page">
    <div class="ptask-name">
        <p>
      <p>{{ $project->name }}/ {{ $task->name }}</p>
        </p>
    </div>
    <div class="pcomments">
        <div class="project-task-comments">
            <h2>Comments</h2>
            <div id="activities-list">
                <!-- Activities will be dynamically loaded here -->
            </div>

            <!-- Sticky Add Activity Section -->
            <div id="add-activity-section" class="sticky-section">
                <form id="add-activity-form" action="{{route('projectsActivities.store')}}" method="POST">
                    @csrf
                    <input type="hidden" name="task_id" id="activity-task-id" value="{{ $task->id ?? '' }}">
                    <input type="hidden" name="project_id" value="{{ $task->project->id }}">

                    <input type="text" name="comments" id="activity-details" placeholder="Add Comments..." required>
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
                    // Create a card for each activity
                    const activityCard = document.createElement('div');
                    activityCard.className = 'activity-card';
                    activityCard.innerHTML = `
                        <p><strong>${activity.username}</strong>: ${activity.comments}</p>
                        <p>Date: ${activity.date} (${activity.dayname})</p>
                        <p>Time: ${new Date(activity.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', hour12: true })}</p>
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
    @endsection