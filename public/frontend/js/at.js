$(document).ready(function() {
    $('#activity-details').atwho({
        at: "@",
        data: [], // Populate with AJAX as needed
        displayTpl: '<li>${username}</li>',
        insertTpl: '@${username}',
        delay: 200,
        callbacks: {
            remoteFilter: function(query, callback) {
                $.ajax({
                    url: '/user-search',
                    type: 'GET',
                    data: { query: query },
                    success: function(data) {
                        callback(data);
                    }
                });
            }
        }
    });
});
