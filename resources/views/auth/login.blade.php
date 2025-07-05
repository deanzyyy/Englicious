@extends('layouts.auth')

@section('content')
<div class="min-h-screen flex items-center justify-center">
    <div class="bg-[#211F27] p-8 rounded-lg shadow-lg w-full max-w-md border border-pink-500">
        <h2 class="text-2xl font-semibold text-white mb-6 text-center">Login to Englicious</h2>
        
        @if (session('success'))
            <div class="bg-green-500 text-white p-3 rounded-lg mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-500 text-white p-3 rounded-lg mb-4">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf
            
            <div>
                <label for="email" class="block text-gray-400 mb-1">Email</label>
                <input type="email" name="email" id="email" required 
                    class="w-full p-2 rounded border border-gray-700 bg-[#2A2833] text-white focus:border-pink-500 focus:outline-none"
                    value="{{ old('email') }}">
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-gray-400 mb-1">Password</label>
                <input type="password" name="password" id="password" required 
                    class="w-full p-2 rounded border border-gray-700 bg-[#2A2833] text-white focus:border-pink-500 focus:outline-none">
                @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="role" class="block text-gray-400 mb-1">Login as</label>
                <select name="role" id="role" required 
                    class="w-full p-2 rounded border border-gray-700 bg-[#2A2833] text-white focus:border-pink-500 focus:outline-none">
                    <option value="admin">Admin</option>
                    <option value="teacher">Teacher</option>
                    <option value="student">Student</option>
                </select>
            </div>

            <div class="flex items-center justify-between">
                <label class="flex items-center text-sm text-gray-400">
                    <input type="checkbox" name="remember" class="mr-2">
                    Remember me
                </label>
                <a href="#" class="text-pink-500 hover:text-pink-400 text-sm">Forgot password?</a>
            </div>

            <button type="submit" 
                class="w-full py-2 px-4 bg-gradient-to-r from-pink-500 to-orange-500 text-white rounded-lg hover:opacity-90 transition-opacity">
                Login
            </button>

            <p class="text-center text-gray-400 text-sm">
                Don't have an account? 
                <a href="{{ route('register') }}" class="text-pink-500 hover:text-pink-400">Register</a>
            </p>
        </form>
    </div>
</div>
@endsection 