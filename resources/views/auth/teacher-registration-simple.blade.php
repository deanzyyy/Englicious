@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-[#211F27] via-[#2A2833] to-[#1A1A1A] p-6">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-5xl font-bold text-white mb-4 flex items-center justify-center gap-4">
                <i class="fi fi-rr-user-add text-pink-500 text-6xl"></i>
                Teacher Registration
            </h1>
            <p class="text-gray-400 text-lg">Register new teachers to join the Englicious platform</p>
        </div>

        <!-- Alerts -->
        @if (session('success'))
            <div class="max-w-2xl mx-auto mb-8 bg-green-500 text-white p-4 rounded-xl">
                <div class="flex items-center gap-3">
                    <i class="fi fi-rr-check text-xl"></i>
                    <span class="font-semibold">{{ session('success') }}</span>
                </div>
            </div>
        @endif
        
        @if ($errors->any())
            <div class="max-w-2xl mx-auto mb-8 bg-red-500 text-white p-4 rounded-xl">
                <div class="flex items-center gap-3 mb-2">
                    <i class="fi fi-rr-exclamation text-xl"></i>
                    <span class="font-semibold">Please fix the following errors:</span>
                </div>
                <ul class="list-disc pl-6 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form -->
        <div class="max-w-2xl mx-auto">
            <form method="POST" action="{{ route('teacher.registration.simple.register') }}" class="space-y-8">
                @csrf
                
                <!-- Personal Info -->
                <div class="bg-[#2A2833]/50 rounded-2xl p-8 border border-pink-500/20">
                    <h3 class="text-2xl font-bold text-white mb-6 flex items-center gap-3">
                        <i class="fi fi-rr-user text-pink-500"></i>
                        Personal Information
                    </h3>
                    
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-gray-300 mb-3 font-medium">Full Name</label>
                            <input type="text" name="name" id="name" required 
                                class="w-full p-4 rounded-xl border-2 border-gray-600 bg-[#211F27] text-white focus:border-pink-500 focus:outline-none text-lg" 
                                placeholder="Enter teacher's full name"
                                value="{{ old('name') }}">
                        </div>
                        
                        <div>
                            <label for="email" class="block text-gray-300 mb-3 font-medium">Email Address</label>
                            <input type="email" name="email" id="email" required 
                                class="w-full p-4 rounded-xl border-2 border-gray-600 bg-[#211F27] text-white focus:border-pink-500 focus:outline-none text-lg" 
                                placeholder="Enter teacher's email"
                                value="{{ old('email') }}">
                        </div>
                    </div>
                </div>

                <!-- Security -->
                <div class="bg-[#2A2833]/50 rounded-2xl p-8 border border-pink-500/20">
                    <h3 class="text-2xl font-bold text-white mb-6 flex items-center gap-3">
                        <i class="fi fi-rr-lock text-pink-500"></i>
                        Security Credentials
                    </h3>
                    
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label for="password" class="block text-gray-300 mb-3 font-medium">Password</label>
                            <input type="password" name="password" id="password" required 
                                class="w-full p-4 rounded-xl border-2 border-gray-600 bg-[#211F27] text-white focus:border-pink-500 focus:outline-none text-lg" 
                                placeholder="Minimum 8 characters">
                        </div>
                        
                        <div>
                            <label for="password_confirmation" class="block text-gray-300 mb-3 font-medium">Confirm Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" required 
                                class="w-full p-4 rounded-xl border-2 border-gray-600 bg-[#211F27] text-white focus:border-pink-500 focus:outline-none text-lg" 
                                placeholder="Re-enter password">
                        </div>
                    </div>
                    
                    <div class="mt-4 p-4 bg-blue-500/10 border border-blue-500/20 rounded-xl">
                        <p class="text-blue-300 text-sm flex items-center gap-2">
                            <i class="fi fi-rr-info"></i>
                            Password must be at least 8 characters long
                        </p>
                    </div>
                </div>

                <!-- Submit -->
                <div class="text-center">
                    <button type="submit" 
                        class="inline-flex items-center gap-3 px-12 py-4 bg-gradient-to-r from-pink-500 to-orange-500 text-white rounded-xl hover:from-pink-600 hover:to-orange-600 transition-all duration-300 font-bold text-lg">
                        <i class="fi fi-rr-user-add text-xl"></i>
                        Register New Teacher
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 