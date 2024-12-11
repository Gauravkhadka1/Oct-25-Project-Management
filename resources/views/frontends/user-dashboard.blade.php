@extends('frontends.layouts.main')

@section('main-container')

<div class="user-profile-page">
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
<style>
    .user-profile-page {
        margin-top: -20px;
    }
    #user-task-board {
        height: 78vh;
    }
     .your-task {
  width: 100%;
  margin-top: -25px;
  margin-bottom: 10px;
  padding-left: 20px;
  font-size: 18px;
  font-weight: 525;
}
.placeholder {
    background: #fff;
    margin: 5px 0;
    border-radius: 5px;
}
.status-n-count-dashboard {
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.task-count-dashboard {
    margin-right: 15px;
    font-weight: 500;
}
</style>
@endsection