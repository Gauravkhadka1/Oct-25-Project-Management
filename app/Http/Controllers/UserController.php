<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function searchUsernames(Request $request)
{
    $query = $request->input('query');
    $users = User::where('username', 'LIKE', "%$query%")->get(['username']); // Adjust as necessary

    return response()->json($users); // Return matching usernames as JSON
}

}
