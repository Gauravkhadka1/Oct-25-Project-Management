<script>
// JavaScript for drag-and-drop functionality

// JavaScript for drag-and-drop functionality

document.addEventListener('DOMContentLoaded', () => {
    const tasks = document.querySelectorAll('.task');
    const columns = document.querySelectorAll('.task-column');

    // Enable drag-and-drop for tasks
    tasks.forEach(task => {
        task.addEventListener('dragstart', () => {
            task.classList.add('dragging');
        });

        task.addEventListener('dragend', () => {
            task.classList.remove('dragging');
        });
    });

    // Adjust column height on page load
    columns.forEach(column => {
        adjustColumnHeight(column);
        observeColumnChanges(column);
    });

    // Adjust column height on window resize
    window.addEventListener('resize', () => {
        columns.forEach(column => adjustColumnHeight(column));
    });

    // Drag-and-drop event listeners for columns
    columns.forEach(column => {
        column.addEventListener('dragover', (e) => {
            e.preventDefault();
        });

        column.addEventListener('drop', (e) => {
            e.preventDefault();
            const draggingTask = document.querySelector('.dragging');
            const previousColumn = draggingTask.closest('.task-column'); // Get the previous column

            if (draggingTask) {
                const taskId = draggingTask.getAttribute('data-task-id');
                const newStatus = column.getAttribute('data-status');

                // Move task to the new column
                column.querySelector('.task-list').appendChild(draggingTask);

                // Adjust heights explicitly after drop
                setTimeout(() => {
                    adjustColumnHeight(previousColumn); // Adjust the column from which the task was removed
                    adjustColumnHeight(column); // Adjust the column where the task was added
                }, 0);

                // AJAX request to update task status in the database
                fetch("{{ route('projects.updateStatus') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
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
            }
        });
    });
});

function adjustColumnHeight(column) {
    const taskList = column.querySelector('.task-list');
    const header = column.querySelector(':scope > div:not(.task-list)');
    const taskHeight = 120; // Fixed height for tasks

    let totalHeight = 0;

    // Include header height
    if (header) totalHeight += header.offsetHeight;

    // Add dynamic task height
    if (taskList) totalHeight += taskList.children.length * taskHeight;

    // Add 30px padding
    column.style.height = (totalHeight + 30) + 'px';
}

function observeColumnChanges(column) {
    const observer = new MutationObserver(() => {
        adjustColumnHeight(column);
    });

    // Observe changes to child elements (e.g., task added/removed)
    observer.observe(column.querySelector('.task-list'), {
        childList: true,
        subtree: false
    });
}

</script>