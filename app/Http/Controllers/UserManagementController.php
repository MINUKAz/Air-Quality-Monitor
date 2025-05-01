<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; // Import the Log facade

class UserManagementController extends Controller
{
    public function index()
    {
        return view('user-management');
    }

    public function getUsers()
    {
        try {
            $users = User::select('id', 'name', 'email', 'role', 'status')->get();
            return response()->json($users);
        } catch (\Exception $e) {
            Log::error('Error fetching users: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch users'], 500);
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'role' => 'required|string|in:Admin,User', // Removed Manager,Editor
            'status' => 'required|string|in:Active,Inactive,Locked',
            'password' => ['required', Password::defaults()]
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'status' => $validated['status'],
            'password' => Hash::make($validated['password'])
        ]);

        return response()->json($user);
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'role' => 'required|string|in:Admin,User', // Removed Manager,Editor
            'status' => 'required|string|in:Active,Inactive,Locked'
        ]);

        $user->update($validated);
        return response()->json($user);
    }

    public function updateStatus(Request $request, User $user)
    {
        $validated = $request->validate([
            'status' => 'required|string|in:Active,Inactive,Locked'
        ]);

        $user->update(['status' => $validated['status']]);
        return response()->json($user);
    }

    public function destroy(User $user)
    {
        try {
            $user->delete();
            return response()->json(['message' => 'User deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete user'], 500);
        }
    }
}