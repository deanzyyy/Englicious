<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // Check if user is admin
        if (auth()->user()->role !== 'admin') {
            return redirect()->route('home')->with('error', 'Access denied. Admin privileges required.');
        }

        $students = User::where('role', 'student')->get();
        $teachers = User::where('role', 'teacher')->get();

        return view('admin.users.index', compact('students', 'teachers'));
    }

    public function destroy($id)
    {
        // Check if user is admin
        if (auth()->user()->role !== 'admin') {
            return redirect()->route('home')->with('error', 'Access denied. Admin privileges required.');
        }

        try {
            $user = User::findOrFail($id);
            
            // Prevent admin from deleting themselves
            if ($user->id === auth()->id()) {
                return back()->with('error', 'You cannot delete your own account.');
            }

            // Prevent deletion of other admin accounts
            if ($user->role === 'admin') {
                return back()->with('error', 'Admin accounts cannot be deleted.');
            }

            $userName = $user->name;
            $userRole = $user->role;
            
            $user->delete();

            return back()->with('success', "{$userRole} '{$userName}' has been successfully deleted.");
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete user. Please try again.');
        }
    }
} 