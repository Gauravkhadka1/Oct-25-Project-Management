@extends('frontends.layouts.main')

@section('main-container')
<div class="task-detail-page">
    <div class="task-details">
        <h2>{{ $task->name }}</h2>
        <p><strong>Category:</strong> {{ $task->category_name }}</p>
        <p><strong>Assigned By:</strong> {{ $task->assignedBy ? $task->assignedBy->username : 'N/A' }}</p>
        <p><strong>Due Date:</strong> {{ $task->due_date }}</p>
        <p><strong>Priority:</strong> {{ $task->priority }}</p>
        <p><strong>Status:</strong> {{ $task->status }}</p>
        <!-- Add more task details as needed -->
    </div>

    <div class="task-comments">
        <h2>Comments</h2>
        <!-- Display or handle comments here -->
    </div>
</div>
@endsection
