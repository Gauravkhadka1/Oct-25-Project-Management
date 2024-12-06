

    $(document).ready(function () {
        $('#message').on('keyup', function (e) {
            const value = $(this).val();
            const atIndex = value.lastIndexOf('@');

            // Show suggestions when '@' is typed and it's the last character
            if (atIndex !== -1 && (value.length === atIndex + 1)) {
                $.ajax({
                    url: '/api/users/search',
                    method: 'GET',
                    data: { query: '' },  // Send an empty query to fetch all usernames
                    success: function (data) {
                        $('#suggestions').empty();
                        if (data.length > 0) {
                            data.forEach(function (user) {
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
                        success: function (data) {
                            $('#suggestions').empty();
                            if (data.length > 0) {
                                data.forEach(function (user) {
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

        $(document).on('click', '#suggestions div', function () {
            const username = $(this).data('username');
            const message = $('#message').val();
            const atIndex = message.lastIndexOf('@');

            const fullMessage = message.substring(0, atIndex + 1) + username + ' ';
            $('#message').val(fullMessage);
            $('#suggestions').hide();
        });

        // Submit the message when the button is clicked
        $('#submit-message').on('click', function () {
            const message = $('#message').val();
            submitMessage(message);
        });

        // Also submit the message when the Enter key is pressed
        $('#message').on('keypress', function (e) {
            if (e.which === 13) { // Enter key
                e.preventDefault(); // Prevent the default behavior (new line)
                const message = $(this).val();
                submitMessage(message);
            }
        });

        // Function to submit the message
        function submitMessage(message) {
            const mentionedUser = extractMentionedUser(message); // Extract mentioned user

            $.ajax({
                url: '/submit-message', // Adjust to the correct endpoint
                method: 'POST',
                data: {
                    message: message,
                    mentioned_user: mentionedUser, // Include mentioned user in the data
                    _token: $('meta[name="csrf-token"]').attr('content') // Include CSRF token if needed
                },
                success: function (response) {
                    console.log(response.message); // Handle success response
                    $('#message').val(''); // Clear the textarea after submission
                },
                error: function (xhr, status, error) {
                    console.error('Error submitting message:', xhr.responseText); // Log the error response for debugging
                }
            });
        }

        // Hide suggestions when clicking outside
        $(document).click(function (e) {
            if (!$(e.target).closest('#suggestions, #message').length) {
                $('#suggestions').hide();
            }
        });
    });

    const message = document.getElementById('message').value;
    const mentionedUser = extractMentionedUser(message); // Function to extract the mentioned user

    fetch('/submit-message', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
        body: JSON.stringify({ message, mentioned_user: mentionedUser }),
    })
    .then(response => response.json())
    .then(data => {
        console.log(data.message);
    });

    function extractMentionedUser(message) {
        const mentionRegex = /@(\w+)/; // Regex to match @username
        const match = message.match(mentionRegex);

        if (match && match[1]) {
            return match[1]; // Return the username or identifier
        }
        return null; // Return null if no mention found
    }
