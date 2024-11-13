@extends('frontends.layouts.main')

@section('main-container')

<div class="profile-page">
        <div class="mytasks">
            <div class="current-tasks">
                <h2>{{ $username }} Tasks</h2>
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