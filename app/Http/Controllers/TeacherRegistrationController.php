<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TeacherRegistrationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Only admin can access
    public function showForm()
    {
        // Check if user is admin
        if (auth()->user()->role !== 'admin') {
            return redirect()->route('home')->with('error', 'Access denied. Admin privileges required.');
        }

        return view('auth.teacher-registration-simple');
    }

    public function register(Request $request)
    {
        // Check if user is admin
        if (auth()->user()->role !== 'admin') {
            return redirect()->route('home')->with('error', 'Access denied. Admin privileges required.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'teacher',
        ]);

        return back()->with('success', 'Teacher registered successfully!');
    }
} 