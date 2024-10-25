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
            <table>
            <thead>
        <tr>
            <th>#</th>
            <th>Project</th>
            <th>Assignie</th>
            <th>Actions</th>
            <th>Time</th>
            <th>Status</th>
            <th>Comment</th>
        </tr>
    </thead>
    <tbody>
        <tr id="task-1">
            <td>1</td>
            <td>Netfinity</td>
            <td>Gaurav K.</td>
            <td>
                <button onclick="startTimer(1)">Start</button>
                <button onclick="pauseTimer(1)">Pause</button>
                <button onclick="stopTimer(1)">Stop</button>
            </td>
            <td id="time-1">00:00:00</td>
            <td><select name="" id="">
                <option value="">To-do</option>
                <option value="">In progress</option>
                <option value="">Completed</option>
            </select></td>
            <td><textarea name="" id="">Good</textarea></td>
        </tr>
        <tr id="task-2">
            <td>1</td>
            <td>Netfinity</td>
            <td>Gaurav K.</td>
            <td>
                <button onclick="startTimer(1)">Start</button>
                <button onclick="pauseTimer(1)">Pause</button>
                <button onclick="stopTimer(1)">Stop</button>
            </td>
            <td id="time-1">00:00:00</td>
            <td><select name="" id="">
                <option value="">To-do</option>
                <option value="">In progress</option>
                <option value="">Completed</option>
            </select></td>
            <td><textarea name="" id="">Good</textarea></td>
        </tr>
        <tr id="task-3">
            <td>1</td>
            <td>Netfinity</td>
            <td>Gaurav K.</td>
            <td>
                <button onclick="startTimer(1)">Start</button>
                <button onclick="pauseTimer(1)">Pause</button>
                <button onclick="stopTimer(1)">Stop</button>
            </td>
            <td id="time-1">00:00:00</td>
            <td><select name="" id="">
                <option value="">To-do</option>
                <option value="">In progress</option>
                <option value="">Completed</option>
            </select></td>
            <td><textarea name="" id="">Good</textarea></td>
        </tr>
    </tbody>
           </table>
        </div>
        <div class="current-tasks">
            <h2>Completed Tasks</h2>
           <table>
            <thead>
                <tr>
                    <td>SN</td>
                    <td>Project</td>
                    <td>Time Took</td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>Netfinity</td>
                    <td>5 hr</td>
                </tr>
                <tr>
                    <td>1</td>
                    <td>Netfinity</td>
                    <td>5 hr</td>
                </tr>
                <tr>
                    <td>1</td>
                    <td>Netfinity</td>
                    <td>5 hr</td>
                </tr>
                <tr>
                    <td>1</td>
                    <td>Netfinity</td>
                    <td>5 hr</td>
                </tr>
            </tbody>
           </table>
        </div>
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
                    
    </main>
@endsection