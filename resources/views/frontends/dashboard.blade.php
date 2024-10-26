@extends('frontends.layouts.main')

@section('main-container')
<main>
    <div class="profile-page"> 
        <div class="profile-name">
            Good Morning <br>{{ $username }}
        </div>  
    </div>
    <div class="mytasks">
        <div class="current-tasks">
            <h2>Current Tasks</h2>
            @forelse($projects as $project)

                @if($project->tasks->isEmpty())
                    <p>No tasks assigned to you in this project.</p>
                @else
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Task</th>
                                <th>Project</th>
                                <th>Assigned by</th>
                                <th>Start date</th>
                                <th>Due date</th>
                                <th>Priority</th>
                                <th>Actions</th>
                                <th>Timestamp</th>
                                <th>Status</th>
                                <th>Comment</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($project->tasks as $index => $task)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $task->name }}</td>
                                    <td>{{ $project->name }}</td>
                                    <td>{{ $task->assignedBy ? $task->assignedBy->username : 'N/A' }}</td> <!-- Show Assigned By Username -->

                                    <td>{{ $task->start_date }}</td>
                                    <td>{{ $task->due_date }}</td>
                                    <td>{{ $task->priority }}</td>
                                    <td>
                                        <button onclick="startTimer({{ $index + 1 }})">Start</button>
                                        <button onclick="pauseTimer({{ $index + 1 }})">Pause</button>
                                        <button onclick="stopTimer({{ $index + 1 }})">Stop</button>
                                    </td>
                                    <td id="time-{{ $index + 1 }}">00:00:00</td>
                                    <td>
                                        <select name="status">
                                            <p>set</p>
                                        </select>
                                    </td>
                                    <td><textarea>{{ $task->comment }}</textarea></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            @empty
                <p>No projects available.</p>
            @endforelse
        </div>
        @if(Auth::check() && Auth::user()->email == $user->email)
            <div class="edit-logout">
                <div class="edit-profile">
                    <a href="{{ route('profile.edit') }}">Edit Profile</a>
                </div>
                <div class="logout">
                    <a href="{{ route('logout') }}">Logout</a>
                </div>
            </div>
        @endif
    </div>
</main>
@endsection
