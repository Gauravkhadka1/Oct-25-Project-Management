<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Frontend\ProjectController;
use App\Http\Controllers\Frontend\TaskController;
use App\Http\Controllers\Frontend\ProspectController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\Frontend\TimerController;
use App\Http\Controllers\EsewaController;
use App\Http\Controllers\Frontend\HomeController; 
use App\Http\Controllers\ContactFormController; 
use App\Http\Controllers\Auth\RegisteredUserController; 
use Illuminate\Support\Facades\Route;

Route::get('/',[HomeController::class, 'index']);

Route::get('/dashboard', [ProfileController::class, 'dashboard'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile/{email}', [ProfileController::class, 'show'])->name('profile.show');

});

require __DIR__.'/auth.php';

Route::post('/postmessage',[ContactFormController::class, 'postmessage']);

// Route::get('/payments',[HomeController::class, 'payments']);
Route::get('/projects', [ProjectController::class, 'projects'])->name('projects.index'); 
Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store'); 
// Route::get('/prospects', [ProspectController::class, 'prospects'])->name('prospects'); 
// Route::post('/prospects', [ProspectController::class, 'prospectstore'])->name('prospects.store');

Route::resource('prospects', ProspectController::class);
Route::resource('projects', ProjectController::class);


Route::get('/search', [HomeController::class, 'search'])->name('search');
Route::get('/admin', [HomeController::class, 'admin'])->name('admin');
Route::get('/user-dashboard', [HomeController::class, 'user'])->name('user');
Route::get('/admin-dashboard', [HomeController::class, 'admindashboard'])->name('admindashboard');

Route::post('/timer/start', [TimerController::class, 'start'])->name('timer.start');
Route::post('/timer/pause', [TimerController::class, 'pause'])->name('timer.pause');
Route::post('/timer/stop', [TimerController::class, 'stop'])->name('timer.stop');

// routes/web.php
Route::post('/activities', [ActivityController::class, 'store'])->name('activities.store');
Route::get('/prospects/{id}/activities', [ActivityController::class, 'showActivities']);
Route::get('/prospects/{prospectId}/activities', [ActivityController::class, 'getActivitiesByProspect']);
// Route::get('/prospects/{prospectId}/activities', [ActivityController::class, 'getActivities']);



Route::delete('/prospects/{id}', [ProspectController::class, 'destroy'])->name('prospects.destroy');
Route::delete('/projects/{id}', [ProjectController::class, 'destroy'])->name('project.destroy');

Route::put('/projects/{project}', [ProjectController::class, 'update'])->name('projects.update');

Route::resource('tasks', TaskController::class);

Route::get('/projects/{projectId}/tasks', [ProjectController::class, 'showTasks'])->name('projects.tasks');

// routes/web.php or routes/api.php

Route::post('/activities/{activity}/like', [ActivityController::class, 'likeActivity']);
Route::post('/activities/{activity}/reply', [ActivityController::class, 'replyToActivity']);

Route::get('/user-search', [UserController::class, 'searchUsernames']);

// web.php
Route::post('/tasks/{task}/start-timer', [TaskController::class, 'startTimer']);
Route::post('/tasks/{task}/pause-timer', [TaskController::class, 'pauseTimer']);




// Route::middleware('guest')->group(function () {
//     Route::get('register', [RegisteredUserController::class, 'create'])
//                 ->name('register');
//     Route::post('register', [RegisteredUserController::class, 'store']);
// });




Route::get('/storage-link',function(){
    $targetFolder = storage_path('app/public/profilepics');
    $linkFolder = $_SERVER['DOCUMENT_ROOT'] . '/storage/profilepics';
    symlink($targetFolder,$linkFolder);
});

Route::get('/tasks', [TaskController::class, 'getTasksForUsername']);




