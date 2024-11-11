<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Frontend\ProjectController;
use App\Http\Controllers\Frontend\TaskController;
use App\Http\Controllers\ProspectTaskController;
use App\Http\Controllers\ClientsController;
use App\Http\Controllers\PaymentTaskController;
use App\Http\Controllers\Frontend\ProspectController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\PaymentsActivityController;
use App\Http\Controllers\Frontend\TimeController;
use App\Http\Controllers\EsewaController;
use App\Http\Controllers\Frontend\HomeController; 
use App\Http\Controllers\ContactFormController; 
use App\Http\Controllers\UserController; 
use App\Http\Controllers\NotificationController; 
use App\Http\Controllers\Auth\RegisteredUserController; 
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\PaymentsController;
use App\Http\Middleware\CheckAllowedEmails;


Route::get('/',[HomeController::class, 'index']);
Route::get('/mention',[HomeController::class, 'mention']);

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


Route::resource('projects', ProjectController::class);

Route::middleware([CheckAllowedEmails::class])->group(function () {
    Route::resource('payments', PaymentsController::class);
    Route::resource('prospects', ProspectController::class);
    Route::resource('all-clients', ClientsController::class);
    Route::post('/payments-activities', [PaymentsActivityController::class, 'store'])->name('payments-activities.store');
Route::get('/prospects/{id}/activities', [ActivityController::class, 'showActivities']);
Route::get('/payments/{Id}/activities', [PaymentsActivityController::class, 'showActivities']);
Route::get('/prospects/{prospectId}/activities', [ActivityController::class, 'getActivitiesByProspect']);
Route::get('/payments/{paymentsId}/activities', [PaymentsActivityController::class, 'getActivitiesByProspect']);
Route::delete('/prospects/{id}', [ProspectController::class, 'destroy'])->name('prospects.destroy');
});



Route::get('/search', [HomeController::class, 'search'])->name('search');
Route::get('/admin', [HomeController::class, 'admin'])->name('admin');
Route::get('/user-dashboard', [HomeController::class, 'user'])->name('user');
Route::get('/admin-dashboard', [HomeController::class, 'admindashboard'])->name('admindashboard');

Route::post('/timer/start', [TimeController::class, 'start'])->name('timer.start');
Route::post('/timer/pause', [TimeController::class, 'pause'])->name('timer.pause');
Route::post('/timer/stop', [TimeController::class, 'stop'])->name('timer.stop');

// routes/web.php
Route::post('/activities', [ActivityController::class, 'store'])->name('activities.store');



// Route::get('/prospects/{prospectId}/activities', [ActivityController::class, 'getActivities']);




Route::delete('/projects/{id}', [ProjectController::class, 'destroy'])->name('project.destroy');

Route::put('/projects/{project}', [ProjectController::class, 'update'])->name('projects.update');

Route::resource('tasks', TaskController::class);
Route::resource('prospectstasks', ProspectTaskController::class);
Route::resource('paymentstasks', PaymentTaskController::class);

Route::get('/projects/{projectId}/tasks', [ProjectController::class, 'showTasks'])->name('projects.tasks');

// routes/web.php or routes/api.php

Route::post('/activities/{activity}/like', [ActivityController::class, 'likeActivity']);
Route::post('/activities/{activity}/reply', [ActivityController::class, 'replyToActivity']);

Route::get('/user-search', [UserController::class, 'searchUsernames']);

// web.php
Route::post('/tasks/{taskId}/start-timer', [TaskController::class, 'startTimer']);
Route::post('/tasks/{taskId}/pause-timer', [TaskController::class, 'pauseTimer']);

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

Route::get('/api/users/search', [UserController::class, 'search']);
// routes/web.php

Route::post('/api/notify-mention', [UserController::class, 'notifyMention']);

Route::post('/submit-message', [MessageController::class, 'submitMessage']);

Route::get('/user-dashboard/{username}', [UserDashboardController::class, 'userDashboard'])->name('user.dashboard');

Route::post('/tasks/update-status-comment', [TaskController::class, 'updateStatusComment'])->name('tasks.updateStatusComment');

Route::post('/tasks/update-comment', [TaskController::class, 'addComment'])->name('tasks.addComment');
Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllRead'])->name('notifications.markAllRead');

Route::get('/add-new-clients', [ClientsController::class, 'addclients']);
Route::resource('clients', ClientsController::class);
// Route::resource('tasks', TaskController::class);
