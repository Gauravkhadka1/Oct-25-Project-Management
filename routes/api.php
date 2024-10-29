<?php 
use App\Http\Controllers\MessageController;
use Illuminate\Support\Facades\Route;

Route::post('/submit-message', [MessageController::class, 'submitMessage']);
Route::get('/users/search', [UserController::class, 'search']);
// Add more API routes as needed...
