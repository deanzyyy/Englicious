@extends('layouts.auth')

@section('content')
<div class="min-h-screen flex items-center justify-center">
    <div class="bg-[#211F27] p-8 rounded-lg shadow-lg w-full max-w-md border border-pink-500">
        <h2 class="text-2xl font-semibold text-white mb-6 text-center">Forgot Password</h2>
        @if (session('success'))
            <div class="bg-green-500 text-white p-3 rounded-lg mb-4">
                {{ session('success') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="bg-red-500 text-white p-3 rounded-lg mb-4">
                {{ $errors->first() }}
            </div>
        @endif
        <form method="POST" action="{{ route('password.manual.check') }}" class="space-y-4">
            @csrf
            <div>
                <label for="email" class="block text-gray-400 mb-1">Email</label>
                <input type="email" name="email" id="email" required autofocus
                    class="w-full p-2 rounded border border-gray-700 bg-[#2A2833] text-white focus:border-pink-500 focus:outline-none"
                    value="{{ old('email') }}">
            </div>
            <div>
                <label for="role" class="block text-gray-400 mb-1">Role</label>
                <select name="role" id="role" required
                    class="w-full p-2 rounded border border-gray-700 bg-[#2A2833] text-white focus:border-pink-500 focus:outline-none">
                    <option value="admin">Admin</option>
                    <option value="teacher">Teacher</option>
                    <option value="student">Student</option>
                </select>
            </div>
            <button type="submit"
                class="w-full py-2 px-4 bg-gradient-to-r from-pink-500 to-orange-500 text-white rounded-lg hover:opacity-90 transition-opacity">
                Next
            </button>
            <p class="text-center text-gray-400 text-sm">
                <a href="{{ route('login') }}" class="text-pink-500 hover:text-pink-400">Back to Login</a>
            </p>
        </form>
    </div>
</div>
@endsection 