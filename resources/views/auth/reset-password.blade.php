@extends('layouts.auth')

@section('content')
<div class="min-h-screen flex items-center justify-center">
    <div class="bg-[#211F27] p-8 rounded-lg shadow-lg w-full max-w-md border border-pink-500">
        <h2 class="text-2xl font-semibold text-white mb-6 text-center">Reset Password</h2>
        @if ($errors->any())
            <div class="bg-red-500 text-white p-3 rounded-lg mb-4">
                {{ $errors->first() }}
            </div>
        @endif
        <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="email" value="{{ $email }}">
            <div>
                <label for="password" class="block text-gray-400 mb-1">New Password</label>
                <input type="password" name="password" id="password" required autofocus
                    class="w-full p-2 rounded border border-gray-700 bg-[#2A2833] text-white focus:border-pink-500 focus:outline-none">
            </div>
            <div>
                <label for="password_confirmation" class="block text-gray-400 mb-1">Confirm Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation" required
                    class="w-full p-2 rounded border border-gray-700 bg-[#2A2833] text-white focus:border-pink-500 focus:outline-none">
            </div>
            <button type="submit"
                class="w-full py-2 px-4 bg-gradient-to-r from-pink-500 to-orange-500 text-white rounded-lg hover:opacity-90 transition-opacity">
                Reset Password
            </button>
        </form>
    </div>
</div>
@endsection 