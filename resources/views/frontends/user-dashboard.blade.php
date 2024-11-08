@extends('frontends.layouts.main')

@section('main-container')

<div class="profile-page">
        <h1>{{ $username }}'s Dashboard</h1>

        <div class="mytasks">
            <div class="current-tasks">
                <h2>Tasks for {{ $username }}</h2>
                @include('partials.task-table', ['tasks' => $tasks, 'prospectTasks' => $prospectTasks, 'paymentTasks' => $paymentTasks, 'projects' => $projects, 'prospects' => $prospects, 'payments' => $payments])
            </div>
        </div>
    </div>
    <script>
     
    document.addEventListener("DOMContentLoaded", function() {
        const userItems = document.querySelectorAll(".username-item");

        userItems.forEach(item => {
            item.addEventListener("click", function() {
                const username = this.dataset.username;
                window.location.href = `/user-dashboard/${username}`;
            });
        });
    });
</script>
@endsection